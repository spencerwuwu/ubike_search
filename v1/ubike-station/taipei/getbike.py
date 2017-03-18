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
    sbi = value["sbi"]
    sna = value["sna"]
    lat = value["lat"]
    lng = value["lng"]
    bikedata['results'].append({'sno': sno,
                                'sna': sna,
                                'sbi': sbi,
                                'lat': lat,
                                'lng': lng
                               })

bikedata = json.dumps(bikedata, ensure_ascii=False).encode('utf8')
f = open('data.json', 'w')
f.write(bikedata)
f.close()
