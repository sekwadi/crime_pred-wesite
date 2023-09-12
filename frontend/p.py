import json
import pandas as pd
import sys
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression

data = sys.argv[1]
data = json.load(data)


new_lat = data[0]
new_lon =data[1]

print(data)