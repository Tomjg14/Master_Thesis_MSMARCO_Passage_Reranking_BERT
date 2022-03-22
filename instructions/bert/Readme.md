# Passage Re-ranking with BERT

****This repository follows the work by [Rodrigo Nogueira & Kyunghyun Cho](https://github.com/nyu-dl/dl4marco-bert).****

This repository contains the code to reproduce the work by Rodrigo Nogueira and Kyunghyun Cho on the [MSMARCO passage ranking task](https://microsoft.github.io/msmarco/). Their work managed to achieve a MRR@10 of 35.87 on the MSMARCO evaluation set and 36.53 on the development set. That is with the BERT LARGE model, but they also experimented with the BERT BASE model. The BERT BASE model leads to ~2 points lower MRR@10 (34.7), but it is faster to train and evaluate. For my thesis I decided to make use of their pre-trained BERT BASE model. Below I will explain how I worked with this model and show that I obtain the same MRR@10 score of 34.7 on the development set of MSMARCO.

## Data

Below you can find all the data that is needed to reproduce the score on the MSMARCO passage ranking task.

| File       | Description           | Size  |
| :-------------: |:-------------:| :-----:|
| [BERT BASE](https://drive.google.com/open?id=1cyUrhs7JaCJTTu-DjFUqP6Bs4f8a6JTX)| BERT BASE model pretrained on MSMARCO | 1.1 GB |
| [MS MARCO Data](https://msmarco.blob.core.windows.net/msmarcoranking/collectionandqueries.tar.gz)| Queries, Passages, and Relevance Labels (05/03/2019) |   1 GB |
| [Anserini's BM25 Output](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Reranking_BERT/raw/master/data/aws/anserini_output/run_development_small_top1000.tsv) | Anserini's BM25 top 1000 ranking MSMARCO dev set | 126 MB |

## Initial BM25 Top 1000 Ranking

Before you can start re-ranking with BERT you will need to produce a top 1000 per query with BM25. I made use of Anserini's implementation of BM25 to produce this initial ranking. In a previous tutorial I go over the details on how to initialize Anserini and perform document ranking with BM25. You can find this detailed tutorial [here](https://tomjg14.github.io/Master_Thesis_MSMARCO_Passage_Reranking_BERT/instructions/rurevm_setup/anserini_bm25). That tutorial shows you exactly how to perform document ranking with BM25 on the development set of MSMARCO with 1000 hits per query. Which is exactly what we need to reproduce the work of Rodgrio Nogueira & Kyunghyun Cho. You could also just download the ranking from the link under the Data section.

## Re-ranking with BERT

After downloading all needed MSMARCO files and running BM25 on the development set you will have the following data to work with:
- **queries.dev.small.tsv**: contains all query ids from the development set of MS MARCO along with the query text (6980 lines big).
- **anserini.output.dev.small.tsv**: contains (per query id) top 1000 of passage ids (6.9M lines big*).
- **collection.tsv**: contains all passage ids along with the passage text (8841823 lines big).

*You would expect that the output from Anserini would be exactly 6980000 lines big as you do 6980 queries times 1000 (top1000). But the output from Anserini's BM25 does not contain a top1000 rank for every query. The file is actually 6974598 lines big.

Next I will explain how to work with the pre-trained BERT model by Rodrigo Nogueira and Kyunghyun Cho to achieve their results on the development set.

I made use of AWS SageMaker notebook instances to run my code. A tutorial on how to use SageMaker can be found [here](https://tomjg14.github.io/Master_Thesis_MSMARCO_Passage_Reranking_BERT/instructions/aws/). As I am running BERT, the Jupyter notebook that I launched inside SageMaker is of type **conda_pytorch_p36**. The SageMaker instance itself is **ml.p2.xlarge** which includes a GPU. 

### Install/Import Libraries

When working with AWS SageMaker you need to make sure all libraries are installed. Use the following commands at the top of your notebook to install them:

```
!pip install pytorch-pretrained-bert
!pip install livelossplot
!pip install nvidia-ml-py3
!pip install unidecode
```

Next import the following libraries:

```python
import pandas as pd
import numpy as np
import os
import json
import unidecode
import re
import torch

from tqdm.auto import tqdm 
from tqdm import tqdm_notebook

from pytorch_pretrained_bert import BertTokenizer, BertModel
from pytorch_pretrained_bert.file_utils import PYTORCH_PRETRAINED_BERT_CACHE
from pytorch_pretrained_bert.modeling import BertForSequenceClassification, BertConfig, WEIGHTS_NAME, CONFIG_NAME, BertForMultipleChoice
from pytorch_pretrained_bert.optimization import BertAdam
from pytorch_pretrained_bert.tokenization import (BasicTokenizer,
                                                  BertTokenizer,
                                                  whitespace_tokenize)
```

### Helper Functions

Include the following helper functions to preprocess the data and run BERT:

```python
# function to get the IDs of the previous queries of a query in a session 
def get_lower_ids(session_df, query_id):
    session_id = int(query_id.split('_')[0])
    current_id = int(query_id.split('_')[1])
    all_ids = [int(x.split('_')[1]) for x in session_df['query_id'].tolist()]
    lower_ids = [x for x in all_ids if x < current_id]
    lower_ids = [str(session_id) + '_' + str(x) for x in lower_ids]
    return lower_ids
```

```python
# function that strips all non-alphanumeric characters
def remove_non_alphanumeric(text):
    text = unidecode.unidecode(str(text))
    text = re.sub(r'[^a-zA-Z0-9]', ' ', text)
    return text
```

```python
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
```

```python
def run_bert(data):
    activations = []
    for i in tqdm_notebook(range(len(data))):
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
```

### Load Data

Now load all data as pandas dataframes.

First set all filenames:

```python
queries_filename = 'queries.dev.small.tsv'
anserini_output_filename = 'run_development_small_top1000.tsv'
output_filename = 'bert_run_development_small_top1000.tsv'
```

I then define my folder structure:

```python
models_dir = "../data/models/"
msmarco_dir = "../data/msmarco_files/"
anserini_output_dir = "../data/anserini_output/"
output_dir = "../data/output/"
```

In the models directory I have my **BERT BASE** model stored.

Next load all data as pandas dataframes:

```python
# MSMARCO collection
msmarco_collection = pd.read_csv(msmarco_dir + 'collection.tsv',delimiter='\t',encoding='utf-8', header=None)
msmarco_collection.columns = ['passage_id', 'passage']

query_subset = pd.read_csv(msmarco_dir + queries_filename,delimiter='\t',encoding='utf-8', header=None)
query_subset.columns = ['query_id', 'query']

query_anserini_output = pd.read_csv(anserini_output_dir + anserini_output_filename,delimiter='\t',encoding='utf-8', header=None)
query_anserini_output.columns = ['query_id', 'passage_id', 'bm25_rank']
```

After this we will need to create a seperate dataframe that can be used to run BERT on. In order to get started with this dataframe I collect all query ids from the development set in a seperate dataframe as a starting point.

```python
top1000_query_ids = pd.DataFrame(list(np.unique(query_anserini_output['query_id'].tolist())))
top1000_query_ids.columns = ['query_id']
```

### Make the BERT Dataframe

To be able to run BERT you will need to make a dataframe according to a specific format. To do this we start with all the query ids from the development set and merge the other dataframes one by one. At the end we will have dataframe with the query texts and passage texts. These texts will need to be preprocessed and set to a specific format as well. 

Lets start with loading tqdm to be able to monitor our progress:

```python
tqdm.pandas()
```

Next we initialize the BERT dataframe:

```python
bert_df = top1000_query_ids.copy()
```

Now we merge the BERT dataframe with the anserini output on the query_ids. 
The resulting dataframe will now contain each query id with its top 1000 passage ids and bm25 rank.

```python
bert_df = bert_df.merge(query_anserini_output,how='left',on=['query_id'])
```

Next we merge the BERT dataframe with the queries dataframe from MS MARCO on query ids.
The dataframe will now also contain the query text for each query id.

```python
bert_df = bert_df.merge(query_subset,how='left',on=['query_id'])
```

After this we merge on the MS MARCO collection dataframe, this time on passage ids.
The dataframe will now also contain the passage text for each passage id.

```python
bert_df = bert_df.merge(msmarco_collection,how='left',on=['passage_id'])
```

Now we will perform some preprocessing to prepare the query and passage texts for BERT.

```python
bert_df['query'] = bert_df['query'].progress_apply(lambda x: remove_non_alphanumeric(x.lower()))
tqdm.pandas()
bert_df['passage'] = bert_df['passage'].progress_apply(lambda x: remove_non_alphanumeric(x.lower()))
```

Finally, we create a column named 'input_text' which will contain the input data for BERT. BERT works with certain tokens (CLS and SEP) that will need to be included:

```python
bert_df['input_text'] = "[CLS] " + bert_df['query'] +" [SEP] " + bert_df['passage'] + " [SEP]"
```

### Load BERT Model

Now we want to load the pre-trained BERT model. 

**Important**: In order to run BERT you will need an notebook instance that includes a GPU. I made use of the **ml.p2.xlarge** instance to run my notebook. While this instance is good to run BERT it will cost more than an instance with less computing power.

```python
bertmodel = BertForSequenceClassification.from_pretrained('bert-base-uncased', 2)
bertmodel.load_state_dict(torch.load(models_dir + 'fine_tuned_bert_base_uncased'))

bertmodel.eval()
bertmodel.to('cuda')

tqdm.pandas()
tokenizer = BertTokenizer.from_pretrained('bert-base-uncased')
```

### Run BERT

Before we run BERT on the data we will need to obtain **indexed tokens** and **segment ids**:

```python
bert_df['indexed_tokens'] = bert_df.progress_apply(lambda row: tokenizer.convert_tokens_to_ids(tokenizer.tokenize(row['input_text'])), axis=1)
bert_df['segment_ids'] = bert_df.progress_apply(lambda row: get_segment_ids_from_index_tokens(row['indexed_tokens']), axis=1)
```

This may take a while as there is a lot of data to process. I use progress_apply to keep track of the progress. 

Next we finally run BERT!

```python
output_df = run_bert(bert_df)
```

Running BERT on the development set with 1000 hits per query took me ~48 hours making use of the **ml.p2.xlarge** instance. You could also split the data in seperate dataframes (be aware that you split on query ids as you want to keep the top 1000 ranking per query). This way you can create several experiments that only take a few hours which you can monitor and check in case something goes wrong (e.g. internet connection fails).

### Compute BERT Score

To obtain the BERT scores per passage and their ranking execute the following code:

```python
output_df['score_bert'] = output_df.progress_apply(lambda row: row['pooled_output'].data[0][1].item(), axis=1)
output_df = output_df.drop(columns=['input_text', 'indexed_tokens', 'segment_ids', 'pooled_output'])
output_df["bert_rank"] = output_df.groupby("query_id")["score_bert"].rank(ascending=0,method='dense')
output_df["bert_rank"] = output_df['bert_rank'].astype(int)
```

This is all that is needed to run BERT on the development set.
Now we can save the dataframe and evaluate the re-ranking.

```python
output_df.to_csv(output_dir + output_filename,sep="\t", header=False,index=False)
```

## Evaluate BERT Re-ranking

Now we want to compute the mean reciprocal rank for the first 10 hits per query (MRR@10). If you want more information on MRR I recommend this [article](https://medium.com/swlh/rank-aware-recsys-evaluation-metrics-5191bba16832) on Medium. 

### Install/Import Libraries

First load the following libraries:

```python
!pip install tqdm
import pandas as pd
import numpy as np
from os import listdir
from os.path import isfile, join
from tqdm.auto import tqdm 
from tqdm import tqdm_notebook
```

### Load Data

Then we load the following files:
- **qrels.dev.small.tsv**: this is a MS MARCO file that contains the query ids of the development set with their corresponding relevant passage id.
- **bert_run_development_small_top1000.tsv**: the output from running BERT.

Specify the paths:

```python
msmarco_dir = "../data/msmarco_files/"
anserini_dir = "../data/anserini_output/"
output_dir = "../data/output/"
```

Load the MS MARCO relevance file:

```python
relevance_df = pd.read_csv(msmarco_dir + 'qrels.dev.small.tsv',delimiter='\t',encoding='utf-8',header=None)
relevance_df.columns = ['query_id','label1','passage_id','label2']
relevance_df = relevance_df.drop(columns=['label1','label2'])
```

I decided to split the data in the previous section in order to create smaller experiments with BERT. Therefore I will also explain how to concatenate all those smaller output files in order to evaluate the re-ranking.

First we list all the output files:

```python
bert_filenames = [f for f in listdir(output_dir) if (isfile(join(output_dir, f))) and (f.startswith("bert_run_msmarco_leaderboard"))]

bert_dfs = []
for bert_filename in tqdm_notebook(bert_filenames):
    bert_df = pd.read_csv(output_dir + bert_filename,delimiter='\t',encoding='utf-8', header=None)
    bert_df.columns = ['query_id', 'passage_id', 'bm25_rank', 'query', 'passage', 'bert_score', 'bert_rank']
    bert_dfs.append(bert_df)
 
 bert_rankings = pd.concat(bert_dfs, ignore_index=True)
 ```
 
 ### Compute MRR@10
 
 Lets define a function that computes the MRR given the ground truth and the predictions.
 
 ```python
 def compute_mrr(gt, pred):
    score = 0.0
    best_rank = 11
    for index, row in pred.iterrows():
        current_rank = row['bert_rank']
        if row['passage_id'] in gt:
            if current_rank < best_rank:
                score = 1.0 / (row['bert_rank'])
                best_rank = current_rank
    return score
```

With help of this function we compute the MRR@10 for the re-ranking by BERT.

```python
mrr = 0.0
query_ids = list(np.unique(bert_rankings['query_id'].tolist()))
relevance_df = relevance_df[relevance_df['query_id'].isin(query_ids)]
for query_id in tqdm_notebook(query_ids):
    query_preds_df = bert_rankings[(bert_rankings['query_id'] == query_id) & (bert_rankings['bert_rank'] < 11)]
    gt = relevance_df[relevance_df['query_id'] == query_id]['passage_id'].values.tolist()
    mrr += compute_mrr(gt, query_preds_df)
```

Now execute the following piece of code: 

```python
round((mrr/len(query_ids))*100,1)
```

And you will get a score of **34.7** which is exactly the same as the score achieved by Rodrigo Nogueira and Kyunghyun Cho on the development set from MS MARCO with 1000 hits.
