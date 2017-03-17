#!/usr/bin/env python
import urllib
import gzip
import json
url = "http://data.taipei/youbike"
urllib.urlretrieve(url, "data.gz")
f = gzip.open('data.gz', 'r')
jdata = f.read()
f.close()
data = json.loads(jdata)

bikedata = {'results':[]}

for key,value in data["retVal"].iteritems():
    sno = value["sno"]
    sna = value["sna"]
    bikedata['results'].append({'sno': sno,
                                'sna': sna
                               })

bikedata = json.dumps(bikedata, ensure_ascii=False).encode('utf8')
f = open('data.json', 'w')
f.write(bikedata)

