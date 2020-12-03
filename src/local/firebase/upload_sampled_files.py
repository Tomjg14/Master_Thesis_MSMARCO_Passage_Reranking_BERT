import firebase_admin
import google.cloud
from firebase_admin import credentials, firestore

query_ids = []

with open('sampled_filenames.txt','r') as infile:
    for line in infile:
        query_ids.append(line.split('.')[0])

def chunkIt(seq, num):
    avg = len(seq) / float(num)
    out = []
    last = 0.0

    while last < len(seq):
        out.append(seq[int(last):int(last + avg)])
        last += avg

    return out

chunks = chunkIt(query_ids,200)

cred = credentials.Certificate("./ServiceAccountKey.json")
app = firebase_admin.initialize_app(cred)

store = firestore.client()
'''
doc_ref = store.collection(u'users')

try:
    docs = doc_ref.get()
    for doc in docs:
        print(u'Doc Data:{}'.format(doc.to_dict()))
except google.cloud.exceptions.NotFound:
    print(u'Missing data')
'''
doc_ref = store.collection(u'batches')
for i,chunk in enumerate(chunks):
    doc_ref.document(str(i+1)).set({'1': chunk[0], '2': chunk[1], '3': chunk[2], 'assessments': 0, "created": firestore.SERVER_TIMESTAMP})

''' 
doc_ref = store.collection(u'batches')
doc_ref.document("4").set({u'name': u'test', u'added': u'just now'})
''' 
