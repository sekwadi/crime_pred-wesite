import sys
import numpy as np
import json
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression


#data = sys.argv[1]

new_data = sys.argv[1]
new_data = json.loads(new_data)


new_lat = new_data[0]
new_lon = new_data[1]

loc = new_data[2]
c_no = new_data[3]

X = np.array(loc)  # Location coordinates
y = np.array(c_no)  # Number of crimes

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train a linear regression model
model = LinearRegression()
model.fit(X_train, y_train)

# Predict the number of crimes for a specific location
new_location = np.array([[new_lat, new_lon]])
predicted_crimes = model.predict(new_location)

print(predicted_crimes)


