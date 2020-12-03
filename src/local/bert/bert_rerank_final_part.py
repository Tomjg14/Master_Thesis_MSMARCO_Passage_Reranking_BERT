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

bert_df_filename = 'small_bert_msmarco_leaderboard_df.tsv'
output_df_filename = 'small_bert_run_msmarco_leaderboard_df.tsv'

print("Load Data")
output_df = pd.read_csv(bert_df_filename,delimiter='\t',encoding='utf-8',header=None)
output_df.columns = ['query_id','passage_id','bm25_rank','query','passage','input_text','indexed_tokens','segment_ids','pooled_output']

print("Score Bert")
tqdm.pandas()
output_df['score_bert'] = output_df.progress_apply(lambda row: row['pooled_output'].data[0][1].item(), axis=1)
output_df = output_df.drop(columns=['input_text', 'indexed_tokens', 'segment_ids', 'pooled_output'])

print("Rank Bert")
output_df["bert_rank"] = output_df.groupby("query_id")["score_bert"].rank(ascending=0,method='dense')
output_df["bert_rank"] = output_df['bert_rank'].astype(int)

print("Save Output")
output_df.to_csv(output_filename,sep="\t", header=False,index=False)
