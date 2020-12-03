#IMPORTS
print("imports")
import pandas as pd
import numpy as np
import os
import json
import unidecode
import re
import torch

from tqdm.auto import tqdm 
from tqdm import tqdm_notebook
from tqdm import notebook
from tqdm import tqdm

from pytorch_pretrained_bert import BertTokenizer, BertModel
from pytorch_pretrained_bert.file_utils import PYTORCH_PRETRAINED_BERT_CACHE
from pytorch_pretrained_bert.modeling import BertForSequenceClassification, BertConfig, WEIGHTS_NAME, CONFIG_NAME, BertForMultipleChoice
from pytorch_pretrained_bert.optimization import BertAdam
from pytorch_pretrained_bert.tokenization import (BasicTokenizer, BertTokenizer, whitespace_tokenize)

def run_bert(data):
    activations = []
    for i in tqdm(range(len(data))):
        # convert inputs to PyTorch tensors
        tokens = data.iloc[i]['indexed_tokens']
        segment_ids = data.iloc[i]['segment_ids']
        
        # make sure the input fits
        token_size_diff = len(tokens) - 512
        if token_size_diff > 0:
            tokens = [tokens[0]] + tokens[token_size_diff:]
            segment_ids = [segment_ids[0]] + segment_ids[token_size_diff:]

        tokens_tensor = torch.tensor([tokens])
        segments_tensors = torch.tensor([segment_ids])

        # set everything to run on GPU
        tokens_tensor = tokens_tensor.to('cuda')
        segments_tensors = segments_tensors.to('cuda')

        with torch.no_grad():
            prediction = bertmodel(tokens_tensor, segments_tensors) 
            activations.append(prediction)

    data['pooled_output'] = activations
    return data

bert_df_filename = 'bert_msmarco_leaderboard_df_subset_1.tsv'
output_df_filename = 'bert_run_msmarco_leaderboard_df_subset_1.tsv'

print("Load Data")
bert_df = pd.read_csv(bert_df_filename,delimiter='\t',encoding='utf-8',header=None)
bert_df.columns = ['query_id','passage_id','bm25_rank','query','passage','input_text','indexed_tokens','segment_ids']

print("Map str to list of ints")
tqdm.pandas()
bert_df['indexed_tokens'] = bert_df['indexed_tokens'].progress_apply(lambda x: json.loads(x))
bert_df['segment_ids'] = bert_df['segment_ids'].progress_apply(lambda x: json.loads(x))

bertmodel = BertForSequenceClassification.from_pretrained('bert-base-uncased', 2)
bertmodel.load_state_dict(torch.load('fine_tuned_bert_base_uncased'))

bertmodel.eval()
bertmodel.to('cuda')

tokenizer = BertTokenizer.from_pretrained('bert-base-uncased')

print("Run Bert")

output_df = run_bert(bert_df)

print("Rank BERT")
output_df['score_bert'] = output_df.progress_apply(lambda row: row['pooled_output'].data[0][1].item(), axis=1)
output_df = output_df.drop(columns=['input_text', 'indexed_tokens', 'segment_ids', 'pooled_output'])

output_df["bert_rank"] = output_df.groupby("query_id")["score_bert"].rank(ascending=0,method='dense')
output_df["bert_rank"] = output_df['bert_rank'].astype(int)

output_df.to_csv(output_df_filename,sep="\t", header=False,index=False)
