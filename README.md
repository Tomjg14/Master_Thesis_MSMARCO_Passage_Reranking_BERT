# Re-ranking BERT; Revisiting Passage Re-Ranking with BERT on MS MARCO

## This Repository

This repository contains the code that I used for my master thesis. 

Any questions related to this repository or my thesis can be send to [tomjg@hotmail.nl](mailto:tomjg@hotmail.nl).

I included instructions on:

- How to connect to the rure-vm server (for Radboud University students): [Getting Started](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/rurevm_setup/).
- How to transfer files between your local machine and the rure-vm server: [File Transfer](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/rurevm_setup/file_transfer).
- How to initialize Anserini to use BM25 for document ranking: [Anserini BM25](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/rurevm_setup/anserini_bm25).
- How to use Google Firebase to set up an environment to authenticate users and to collect and store user data: [Firebase](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/firebase/).
- How to use Amazon Web Services (AWS) to set up and run BERT on the cloud: [AWS](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/aws/).
- How to reproduce the results with BERT by [Rodrigo Nogueira and Kyunghyun Cho](https://arxiv.org/abs/1901.04085): [BERT](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/instructions/bert/).

Below you will find:
- [Thesis Outline](#thesis-outline).
- [Methods](#methods)
- [Results](#results)
- [Relevant Links](#relevant-links)
- [Toolkits and Packages used](#toolkits-and-packages)

## Thesis Outline

My thesis focuses on re-evaluating BERT on its performance on passage re-ranking using the MS MARCO datset. Currently, the MS MARCO dataset contains only a few relevant passages per query, mostly only one. My hypothesis is that the dataset as is, is inaccurate and that in fact the dataset contains more relevant passages per query.

Therefore, I designed an online assessment which helped to gather more query-passage pair relevancy assessments for the MS MARCO passage ranking dataset. With these additional labels I then re-evaluate BERT on the task of passage re-ranking. 

In contrast to the original relevancy labels which were binary (irrelevant or relevant), I gathered graded relevance labels which make it possible to perform additional analyses to see if performance on the passage ranking task is affected when dealing with non-binary relevance labels. 

The main goal of my thesis is to take a closer look at the performance of BERT on the MS MARCO passage ranking task. To see if its performance on this task truly reflects the state-of-the-art status that BERT receives from the community or if the scarce number of relevancy labels causes a distorted picture of reality.

[Initial Thesis Proposal](http://tomjanssengroesbeek.nl/Master_Thesis_MSMARCO_Passage_Ranking_BERT/files/Master_Thesis_Proposal.pdf) (26-03-2020)

## Methods

In order to gather more query-passage relevancy assessments, I created a online assessment by making use of Google's Firebase. On this webpage the subjects who consented to help me with my research, are presented with numerous queries with each a set of 20 passages returned by the BM25 algorithm in an initial ranking. They are then able to assess those passages on their level of relevancy compared to the query. The scale used for relevancy is 1 (totally irrelevant) to 5 (perfectly relevant). This means that graded relevancy labels are gather instead of binary, which was the case in the original MS MARCO dataset. The idea behind this approach is to enable different kind of analyses. It is always possible to go back to binary by making use of a threshold. I make sure that every query is assessed by at least 3 different assessors. This way if there is disagreement among the assessors we can peform majority voting to decide on a label. 

The BM25 algorithm will be used to obtain an initial passage ranking of the MS MARCO development set. I utilize the BM25 model by Anserini with the standard settings as stated on their github page. Making sure to retrieve a top 100 for each query. I then use the fine-tuned (on MS MARCO) BERT Base model by Rodrigo Nogueira and Kyunghyun Cho to re-rank the initial BM25 passage top 100 ranking. This ranking will then be evaluated making use of both the original relevance labels and the newly acquired relevance labels. 

In order to evaluate BERT's performance on the passage ranking task I make use of the MRR@10. As this metric is also used by MS MARCO for their leaderboard showing the performances of different models on their tasks. Aside from MRR@10 I will also compute the precision, mean average precision and normalized discounted cumulative gain of BERT's ranking. The NDCG score will be interesting to compute as this was previously not possible due to the binary relevance labels. BERT's performance will be compared to the BM25 baseline and its own performance as we make use of different label datasets.

## Results

**Number of Queries: 42**

**Number of Hits: 100**

**Datasets: original relevance labels (MS MARCO); new relevance labels binary threshold = 2 (Liberal); new relevance labels binary threshold = 3 (Strict); new relevance labels multi-label (Graded)**

**Metrics: MRR; MFR; Precision; NDCG**

**Models: BM25 and BERT**

### Experiment 1: BM25 old relevance labels vs. new relevance labels

| Metric  | MS MARCO  | Liberal |
|:------: |:--------: |:------: |
| MRR@5   | 0.471     | 0.976   |
| MRR@10  | 0.491     | 0.976   |
| MFR@5   | 3.26      | 1.05    |
| MFR@10  | 4.14      | 1.05    |
| P@5     | 0.143     | 0.805   |
| P@10    | 0.086     | 0.671   |

| Metric  | MS MARCO  | Strict  |
|:------: |:--------: |:------: |
| MRR@5   | 0.471     | 0.917   |
| MRR@10  | 0.491     | 0.917   |
| MFR@5   | 3.26      | 1.24    |
| MFR@10  | 4.14      | 1.24    |
| P@5     | 0.143     | 0.633   |
| P@10    | 0.086     | 0.505   |


### Experiment 2: BERT old relevance labels vs. new relevance labels

| Metric  | MS MARCO  | Liberal |
|:------: |:--------: |:------: |
| MRR@5   | 0.718     | 0.948   |
| MRR@10  | 0.728     | 0.948   |
| MFR@5   | 2.02      | 1.12    |
| MFR@10  | 2.48      | 1.12    |
| P@5     | 0.171     | 0.671   |
| P@10    | 0.093     | 0.502   |

| Metric  | MS MARCO  | Strict  |
|:------: |:--------: |:------: |
| MRR@5   | 0.718     | 0.925   |
| MRR@10  | 0.728     | 0.925   |
| MFR@5   | 2.02      | 1.17    |
| MFR@10  | 2.48      | 1.17    |
| P@5     | 0.171     | 0.600   |
| P@10    | 0.093     | 0.426   |

### Experiment 3: BM25 vs BERT (MS MARCO dataset)

| Metric  | BM25      | BERT    |
|:------: |:--------: |:------: |
| MRR@5   | 0.471     | 0.718   |
| MRR@10  | 0.491     | 0.728   |
| MFR@5   | 3.26      | 2.02    |
| MFR@10  | 4.14      | 2.48    |
| P@5     | 0.143     | 0.171   |
| P@10    | 0.086     | 0.093   |

### Experiment 4: BM25 vs BERT (Liberal dataset)

| Metric  | BM25      | BERT    |
|:------: |:--------: |:------: |
| MRR@5   | 0.976     | 0.948   |
| MRR@10  | 0.976     | 0.948   |
| MFR@5   | 1.05      | 1.12    |
| MFR@10  | 1.05      | 1.12    |
| P@5     | 0.805     | 0.671   |
| P@10    | 0.671     | 0.502   |


### Experiment 5: BM25 vs BERT (Strict dataset)

| Metric  | BM25      | BERT    |
|:------: |:--------: |:------: |
| MRR@5   | 0.917     | 0.925   |
| MRR@10  | 0.917     | 0.925   |
| MFR@5   | 1.24      | 1.17    |
| MFR@10  | 1.36      | 1.17    |
| P@5     | 0.633     | 0.600   |
| P@10    | 0.505     | 0.426   |

### Experiment 6: BM25 vs BERT (Graded dataset)

| Metric  | BM25      | BERT    |
|:------: |:--------: |:------: |
| NDCG@5  | 0.787     | 0.780   |
| NDCG@10 | 0.828     | 0.784   |
| NDCG@20 | 0.915     | 0.824   |


## Relevant Links

**MS MARCO**: [https://microsoft.github.io/msmarco/](https://microsoft.github.io/msmarco/)

**Passage and Document Retrieval (26-10-2018 - Present)**: [https://msmarco.blob.core.windows.net/msmarcoranking/collectionandqueries.tar.gz](https://msmarco.blob.core.windows.net/msmarcoranking/collectionandqueries.tar.gz)

## Toolkits and Packages

**Anserini**: [https://github.com/castorini/anserini](https://github.com/castorini/anserini)

**Pytorch Transformers**: [https://github.com/huggingface/pytorch-transformers](https://github.com/huggingface/pytorch-transformers)

**Passage re-ranking with BERT**: [https://github.com/nyu-dl/dl4marco-bert](https://github.com/nyu-dl/dl4marco-bert)

## Files

**Query Subset Thesis**: [experiment_query_subset.tsv](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Ranking_BERT/raw/master/data/firebase/additional_files/experiment_query_subset.tsv)

**Thesis Code**: [src/local/local_used_code/](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Ranking_BERT/raw/master/src/local/local_used_code)

**Thesis Data**: [data/local/local_used_data/](https://github.com/Tomjg14/Master_Thesis_MSMARCO_Passage_Ranking_BERT/raw/master/data/local/local_used_data)
