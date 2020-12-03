import firebase_admin
from firebase_admin import credentials, firestore
from firebase_admin import db
import os

import json
import pandas as pd

cwd = os.getcwd()

cred = credentials.Certificate("serviceAccountKey.json")
firebase_admin.initialize_app(cred)
db = firestore.client()
docs = db.collection('batches').stream()

def make_dir(dir_path):
    if not os.path.exists(dir_path):
        os.makedirs(dir_path)
    return dir_path

queries = []

for doc in docs:
    batch_data = db.collection('batches').document(doc.id).get().to_dict()
    queries = queries + [batch_data['1'], batch_data['2'], batch_data['3']]

query_subset_dataframe = pd.DataFrame(queries)
query_subset_dataframe.to_csv('thesis_firebase_query_subset.tsv',sep="\t", header=False,index=False)
        



    
