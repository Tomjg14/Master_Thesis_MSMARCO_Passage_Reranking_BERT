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

# HELPER FUNCTIONS
print("helper functions")
# function to get the IDs of the previous queries of a query in a session 
def get_lower_ids(session_df, query_id):
    session_id = int(query_id.split('_')[0])
    current_id = int(query_id.split('_')[1])
    all_ids = [int(x.split('_')[1]) for x in session_df['query_id'].tolist()]
    lower_ids = [x for x in all_ids if x < current_id]
    lower_ids = [str(session_id) + '_' + str(x) for x in lower_ids]
    return lower_ids

# function that strips all non-alphanumeric characters
def remove_non_alphanumeric(text):
    text = unidecode.unidecode(str(text))
    text = re.sub(r'[^a-zA-Z0-9]', ' ', text)
    return text

# function that returns a list of segment ids based on indexed tokens (BERT)
def get_segment_ids_from_index_tokens(indexed_tokens):
    segment_ids = []
    sep = False
    for i, token in enumerate(indexed_tokens):
        if token == 102:
            sep = True
        if sep:
            segment_ids.append(1)
        else:
            segment_ids.append(0)
    return segment_ids

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

print("load data")
# LOAD DATA

query_subset_filename = 'queries.dev.small.tsv'
anserini_output_filename = 'run_development_small_top1000.15.5.2020.tsv'
output_filename = 'bert_run_development_small_top1000.tsv'

# MSMARCO collection
msmarco_collection = pd.read_csv('collection.tsv',delimiter='\t',encoding='utf-8', header=None)
msmarco_collection.columns = ['passage_id', 'passage']

# MSMARCO queries
query_subset = pd.read_csv(query_subset_filename,delimiter='\t',encoding='utf-8', header=None)
query_subset.columns = ['query_id', 'query']

# Anserini output
query_anserini_output = pd.read_csv(anserini_output_filename,delimiter='\t',encoding='utf-8', header=None)
query_anserini_output.columns = ['query_id', 'passage_id', 'bm25_rank']

top1000_query_ids = pd.DataFrame(list(np.unique(query_anserini_output['query_id'].tolist())))
top1000_query_ids.columns = ['query_id']

print("make bert dataframe")
# MAKE BERT DATAFRAME

tqdm.pandas()
bert_df = top1000_query_ids.copy()
bert_df = bert_df.merge(query_anserini_output,how='left',on=['query_id'])
bert_df = bert_df.merge(query_subset,how='left',on=['query_id'])
bert_df = bert_df.merge(msmarco_collection,how='left',on=['passage_id'])
bert_df['query'] = bert_df['query'].progress_apply(lambda x: remove_non_alphanumeric(x.lower()))
tqdm.pandas()
bert_df['passage'] = bert_df['passage'].progress_apply(lambda x: remove_non_alphanumeric(x.lower()))
bert_df['input_text'] = "[CLS] " + bert_df['query'] +" [SEP] " + bert_df['passage'] + " [SEP]"

print("load bert model")
# LOAD BERT MODEL

bertmodel = BertForSequenceClassification.from_pretrained('bert-base-uncased', 2)
bertmodel.load_state_dict(torch.load('fine_tuned_bert_base_uncased'))

bertmodel.eval()
bertmodel.to('cuda')

tokenizer = BertTokenizer.from_pretrained('bert-base-uncased')

print("run bert")
# RUN BERT

bert_df['indexed_tokens'] = bert_df.progress_apply(lambda row: tokenizer.convert_tokens_to_ids(tokenizer.tokenize(row['input_text'])), axis=1)

bert_df['segment_ids'] = bert_df.progress_apply(lambda row: get_segment_ids_from_index_tokens(row['indexed_tokens']), axis=1)

bert_df.to_csv("bert_msmarco_leaderboard_df",sep='\t',header=False,index=False)

output_df = run_bert(bert_df)

output_df.to_csv(output_filename,sep="\t", header=False,index=False)

output_df['score_bert'] = output_df.progress_apply(lambda row: row['pooled_output'].data[0][1].item(), axis=1)
output_df = output_df.drop(columns=['input_text', 'indexed_tokens', 'segment_ids', 'pooled_output'])

output_df["bert_rank"] = output_df.groupby("query_id")["score_bert"].rank(ascending=0,method='dense')
output_df["bert_rank"] = output_df['bert_rank'].astype(int)

output_df.to_csv(output_filename,sep="\t", header=False,index=False)
