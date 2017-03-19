# Ubike Search

[Demo](-forest-67545.herokuapp.com/)

This is the backend challenge for the applying to [appier](http://www.appier.com/zh/).   
It is an api that finds the nearest 2 u-bike stations based on latitude and longitude in request parameters.
## Usage:
* `/v1/ubike-station/taipei`
  * Request method `GET`
  * Request Parameters:   
    `lat`: latitude of location   
    `lng`: longitude of location

## Error Handling:
* 1 : all ubike stations are full
* 0 : OK
* -1: invalid latitude or longitude
* -2: given location not in Taipei City
* -3: system error

Return empty list as result while returning. Stations with no bikes will be skipped.

## Response
* Content Type: `application/json`
```
{
    "code": $error-code,
    "result": [
        {
           "station": "$name-of-station", 
           "num_ubike": $number-of-available-ubike
        },
        {
           "station": "$name-of-station", 
           "num_ubike": $number-of-available-ubike
        }
    ]
}
```
* Sample Requests
```
# sample 1
# request
GET /v1/ubike-station/taipei?lat=25.034153&lng=121.568509

# response
Content-Type: application/json

    {
        "code": 0,
        "result": [
            {"station": "捷運象山站", "num_ubike": 10},
            {"station": "世貿二館", "num_ubike": 33}
        ]               
    }

# sample 2
# request
GET /v1/ubike-station/taipei?lat=24.999087&lng=121.327547

# response
Content-Type: application/json

    {
        "code": -2,
        "result": []
    }
```
