# Firebase Data

Firebase was used to store relevance labels provided by assessors.
The original assessor data can be found in the directory named 'assessors'.
In this directory you will find several subdirectories named after the assessor ids.
Each subdirectory contains:
- user information
- a list of assessed query ids
- directory for each query with 20 txt files for each passage labeled.

Aside from the original user input, you will find the analyzed input in the directory named 'relevance_labels'. This directory contains all the assessor data grouped in a file named 'data.json'.
This data has been analyzed and new relevance files (similar to the original ms marco relevance file) have been created:
- original_assessor_relevance_labels.tsv: contain only the assessor relevant passages
- complete_relevance_labels.tsv: contain both the msmarco relevant and assessor relevant passages
- new_relevance_labels.tsv: preprocessed dataset used for thesis; only contains those queries where assessors agreed that msmarco relevant passage is also relevant and where assessors appointed more than 1 relevant passage.

Important:

data.json is structured as follows:
- keys: query_ids
- values: passage data (dictionary)

passage data is structured as follows:
- keys: passage_ids
- values: passage information (list)

passage information is a list of variable length depending on the number of assessors that processed the query:
- The first element is always the msmarco label (irrelevant/relevant)
- The second element is the assessor's id 
- The third element is the assessor's input

The latter two are repeated for the number of assessors.
Data was structured this way to be able to easily load it as a pandas dataframe.

Finally, the directory 'sampled_files' contains all the query files used for the website. These files contain a list of 20 elements. Every element is a dictionary with information important for the assessors or for data storage:
- query_id: msmarco query id
- passage_id: msmarco passage id
- bm25 ranking: anserini bm25 ranking
- query_text: the actual query text
- passage_text: the actual passage text
- bm25 label: either high (bm25 rank < 21), medium (21 - 80), low (81 - 100) or outside of scope (>100)
- relevance label: msmarco relevance label (either 1 or 0)
