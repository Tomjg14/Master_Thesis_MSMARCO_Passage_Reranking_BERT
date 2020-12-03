import firebase_admin
from firebase_admin import credentials, firestore
from firebase_admin import db
import os

import json

cwd = os.getcwd()

cred = credentials.Certificate("serviceAccountKey.json")
firebase_admin.initialize_app(cred)
db = firestore.client()
docs = db.collection('users').stream()

def make_dir(dir_path):
    if not os.path.exists(dir_path):
        os.makedirs(dir_path)
    return dir_path

user_dict = {}
query_user_dict = {}

for doc in docs:
    user_dict[doc.id] = {}
    queries = db.collection('users').document(doc.id).collections()
            
    for query in queries:
        if not query.id in query_user_dict.keys():
            query_user_dict[query.id] = [doc.id]
        else:
            query_user_dict[query.id].append(doc.id)
        user_dict[doc.id][query.id] = {}
        passages = db.collection('users').document(doc.id).collection(query.id).stream()
        for passage in passages:
            data = passage.to_dict()
            user_dict[doc.id][query.id][passage.id] = [data['query_id'],data['passage_id'],data['msmarco_label'],doc.id,data['subject_label']]

output_dict = {}

for query_id in query_user_dict.keys():
    output_dict[query_id] = {}
    user_ids = query_user_dict[query_id]
    for user_id in user_ids:
        user_data = user_dict[user_id][query_id]
        for passage_id in user_data.keys():
            if not passage_id in output_dict[query_id].keys():
                output_dict[query_id][passage_id] = user_data[passage_id][2:]
            else:
                output_dict[query_id][passage_id] = output_dict[query_id][passage_id] + user_data[passage_id][3:]
    
        
        
with open('data.json', 'w') as outfile:
    json.dump(output_dict,outfile)
        



    
