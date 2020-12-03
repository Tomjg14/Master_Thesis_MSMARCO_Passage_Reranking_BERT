# Anserini on Rurevm

For my thesis I made use of the Anserini toolkit and thus I started with the instructions on running BM25 baselines on the MS MARCO *passage* ranking task.
These instructions can be found on the following page: [https://github.com/castorini/anserini/blob/master/docs/experiments-msmarco-passage.md](https://github.com/castorini/anserini/blob/master/docs/experiments-msmarco-passage.md).

Other pages that were consulted when following the instructions:

<a name="anserini-footnote">1</a>: [https://github.com/castorini/anserini](https://github.com/castorini/anserini)

<a name="pyserini-footnote">2</a>: [https://github.com/castorini/anserini/blob/master/docs/pyserini.md](https://github.com/castorini/anserini/blob/master/docs/pyserini.md)

<a name="pyserini-footnote2">3</a>: [https://github.com/castorini/pyserini](https://github.com/castorini/pyserini)

## Initial Setup 

When connected to the rurevm server and inside the designated directory from which one wants to work, first clone the repo<sup>[1](#anserini-footnote)</sup>.

When cloned follow the following steps from inside the repo:

When this is the first time you are working with maven on rurevm, you will most probably get an error indicating that the JAVA_HOME variable is not set properly.

If this is the case, follow the following steps to fix it:

```
sudo add-apt-repository ppa:openjdk-r/ppa
sudo apt-get update -q
sudo apt install -y openjdk-11-jdk
export JAVA_HOME=/usr/lib/jvm/java-11-openjdk-amd64/
export PATH=$JAVA_HOME/bin:$PATH
```

Then perform the following commands:

```
mvn clean package appassembler:assemble
cd eval
tar xvfz trec_eval.9.0.4.tar.gz && cd trec_eval.9.0.4 && make
cd ../
cd ndeval
make
```

## Start with the Anserini instructions

### Data Prep

Next make a new working directory:

```
sudo mkdir msmarco-passage
chown <user> msmarco-passage

wget https://msmarco.blob.core.windows.net/msmarcoranking/collectionandqueries.tar.gz -P msmarco-passage
tar -xzvf msmarco-passage/collectionandqueries.tar.gz -C msmarco-passage
```

I made the msmarco-passage directory just outside the anserini repo, which means that the next commands will differ from the anserini instructions.

Then convert the MS MARCO tsv collection into Anserini's jsonl files (which have one json object per line):

```
python anserini/src/main/python/msmarco/convert_collection_to_jsonl.py \
 --collection_path msmarco-passage/collection.tsv --output_folder msmarco-passage/collection_jsonl
```

The above script should generate 9 jsonl files in ```msmarco-passage/collection_jsonl```, each with 1M lines (except for the last one, which should have 841,823 lines).

Now you can index these docs as a JsonCollection using Anserini:

```
sh anserini/target/appassembler/bin/IndexCollection -collection JsonCollection \
 -generator LuceneDocumentGenerator -threads 9 -input msmarco-passage/collection_jsonl \
 -index msmarco-passage/lucene-index-msmarco -storePositions -storeDocvectors -storeRawDocs
```

You should now have an index with 8,841,823 documents.

### Retrieving and Evaluating the Dev set

This section is almost identical to the Anserini instructions, but is still included to be able to check if the indexing has succeeded and similar results are obtained. I also included it here for convenience reasons. 

Since queries of the set are too many (+100k), it would take a long time to retrieve all of them. To speed this up, we use only the queries that are in the qrels file:

```
python anserini/src/main/python/msmarco/filter_queries.py --qrels msmarco-passage/qrels.dev.small.tsv \
 --queries msmarco-passage/queries.dev.tsv --output_queries msmarco-passage/queries.dev.small.tsv
```

The output queries file should contain 6980 lines.

**IMPORTANT**

Before moving on with the Anserini instructions, you need to make sure that the pyserini module is installed at the rurevm server<sup>[2](#pyserini-footnote)</sup><sup>[3](#pyserini-footnote2)</sup>.

```
pip install Cython
pip install pyjnius
pip install pyserini
wget -O anserini-0.6.0-fatjar.jar https://search.maven.org/remotecontent?filepath=io/anserini/anserini/0.6.0/anserini-0.6.0-fatjar.jar
export ANSERINI_CLASSPATH="/path/to/fatjar/directory"
```

We can now retrieve this smaller set of queries:

```
python anserini/src/main/python/msmarco/retrieve.py --hits 1000 --threads 1 \
 --index msmarco-passage/lucene-index-msmarco --qid_queries msmarco-passage/queries.dev.small.tsv \
 --output msmarco-passage/run.dev.small.tsv
```

Note that by default, the above script uses BM25 with tuned parameters ```k1=0.82```, ```b=0.68``` (more details below). The option ```-hits``` specifies the number of documents per query to be retrieved. Thus, the output file should have approximately 6980 * 1000 = 6.9M lines.

Finally, we can evaluate the retrieved documents using the official MS MARCO evaluation script:

```
python anserini/src/main/python/msmarco/msmarco_eval.py \
 msmarco-passage/qrels.dev.small.tsv msmarco-passage/run.dev.small.tsv
```

And the output should be like this

```
#####################
MRR @10: 0.18741227770955546
QueriesRanked: 6980
#####################
```

You can also use the official TREC evaluation tool, ```trec_eval```, to compute other metrics than MRR@10. For that you first need to convert runs and qrels files to the TREC format:

```
python anserini/src/main/python/msmarco/convert_msmarco_to_trec_run.py \
 --input_run msmarco-passage/run.dev.small.tsv --output_run msmarco-passage/run.dev.small.trec
 
 python anserini/src/main/python/msmarco/convert_msmarco_to_trec_qrels.py \
 --input_qrels msmarco-passage/qrels.dev.small.tsv --output_qrels msmarco-passage/qrels.dev.small.trec
```

And run the ```trec_eval```tool:

```
anserini/eval/trec_eval.9.0.4/trec_eval -c -mrecall.1000 -mmap \
 msmarco-passage/qrels.dev.small.trec msmarco-passage/run.dev.small.trec
```

The output should be:

```
map                   	all	0.1957
recall_1000           	all	0.8573
```

[Homepage](http://tomjanssengroesbeek.nl/Master_Thesis_CoAs_BM25_BERT/)
