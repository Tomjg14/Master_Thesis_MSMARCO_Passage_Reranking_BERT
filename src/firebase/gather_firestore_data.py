import firebase_admin
from firebase_admin import credentials, firestore
from firebase_admin import db
import os

cwd = os.getcwd()

cred = credentials.Certificate("serviceAccountKey.json")
firebase_admin.initialize_app(cred)
db = firestore.client()
docs = db.collection('users').stream()

def make_dir(dir_path):
    if not os.path.exists(dir_path):
        os.makedirs(dir_path)
    return dir_path

for doc in docs:
    user_dir = make_dir(os.path.join(cwd,doc.id))
    with open(os.path.join(user_dir,'user_info.txt'),'w', encoding="utf-8") as outfile:
        outfile.write("user_id:\t\t\t%s\n"%(doc.id))
        user_info = doc.to_dict()
        for k,v in user_info.items():
            outfile.write("%s:\t\t\t%s\n"%(k,v))
    user_collections = db.collection('users').document(doc.id).collections()
    assessed_queries = []
            
    for user_collection in user_collections:
        assessed_queries.append(user_collection.id)
        collection_dir = make_dir(os.path.join(user_dir,user_collection.id))
        assessed_passages = db.collection('users').document(doc.id).collection(user_collection.id).stream()
        for assessed_passage in assessed_passages:
            with open(os.path.join(collection_dir,"%s.txt"%(assessed_passage.id)),'w', encoding="utf-8") as outfile:
                info = assessed_passage.to_dict()
                for k,v in info.items():
                    outfile.write("%s:\t\t\t%s\n"%(k,v))


    with open(os.path.join(user_dir,'assessed_queries.txt'),'w') as outfile:
        for assessed_query in assessed_queries:
            outfile.write("%s\n"%(assessed_query))
    

    
