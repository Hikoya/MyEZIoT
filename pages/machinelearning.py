#!/usr/bin/python
import mysql.connector
import pandas as pd
import time
import requests
import datetime
from sklearn import tree
from time import sleep

while(1):
	db = mysql.connector.connect(host="loragateway.cr6mxzjplutp.ap-southeast-1.rds.amazonaws.com",  # your host, usually localhost
						 user="cl",         # your username
						 password="password",  # your password
						 database="fyp")        # name of the data base

	cur = db.cursor()

	nodes = []
	switches = []
	command = []
	username = []
	description = []
	query = "SELECT threshold , gatewayno , command , username , description FROM switch WHERE threshold IS NOT NULL"
	cur.execute(query)


	for row in cur.fetchall():
		nodes.append(row[0]) #threshold 
		switches.append(row[1]) #gatewayno
		command.append(row[2]) #command
		username.append(row[3]) #username
		description.append(row[4]) #desc

	for position, row in enumerate(nodes):
		
		query2 = "SELECT threshold1 , description FROM nodes WHERE gatewayno = '{0}' AND threshold1 IS NOT NULL".format(row) 
		cur.execute(query2)
		result = cur.fetchall()
		numrow = cur.rowcount
			
		if numrow > 0 :
			in_query = "SELECT 0 + column1, UNIX_TIMESTAMP(timestamp) FROM {0} WHERE column1 is NOT NULL".format(row)
			cur.execute(in_query)
			in_result = cur.fetchall()
			in_numcount = cur.rowcount
			
			if in_numcount > 0:
				dataset = pd.read_sql(in_query, con=db)
				array = dataset.values
				X = array[:,1] #timestamp
				Y = array[:,0] #column values
				X = X.reshape(-1, 1)
		   
		   # switch = switches[position]    
		   # print(switch) 
			  
				features = X
				labels = Y
			#clf = LinearRegression()
				clf = tree.DecisionTreeRegressor()
				clf = clf.fit(features, labels)
				timestamp = int(time.time() + 600) #10 minutes
				predict = clf.predict([[timestamp]])
			
				for threshold in result :
					thres = float(threshold[0])
					desc = threshold[1]
				
				time_result = 0
				time_query = "SELECT smsmtime FROM nodes WHERE gatewayno = '{0}' AND smsmtime is NOT NULL".format(row)
				cur.execute(time_query)
				time_results = cur.fetchall()
				num_row = cur.rowcount
				
				if num_row > 0:
					for time_r in time_results :
						time_result = time_r[0]
						
					time_result = int(time_result)
				
				cur_time = time.time()
				cur_time_int = int(cur_time) 
				difference = cur_time_int - time_result
			
				if predict >= thres and difference >= 900:
					command_used = command[position]
					switch_used = switches[position]
					desc_used = description[position]
					payload = {'command': command_used , 'deviceID': switch_used }
					r = requests.get('http://www.myeziot.com/pages/netpie/netpie-put.php', params=payload)
					print("Triggered Thres1 " + str(command_used) + " " + str(switch_used))
					
					update_time = str(cur_time_int)
					sms_query = 'UPDATE nodes SET smsmtime = " {0} " WHERE gatewayno = " {1} " '.format(update_time,row)
					
					print(sms_query)
					cur.execute(sms_query)
					
					time_1 = str('{:%Y-%m-%d %H:%M:%S}'.format(datetime.datetime.now()))
					
					msg = "" + str(desc_used) + " SET TO " + str(command_used) + " as predicted value of " + str(predict) + " exceed threshold of " + str(thres) + " for " + str(desc) + " Last updated " + time_1; 
					
					switch_user = username[position]
					num_query = "SELECT mobileno , callingcode FROM fyp WHERE username = '{0}' ".format(switch_user)
					cur.execute(num_query)
					for mob in cur.fetchall():
							mobileno = str(mob[0])  
							code = str(mob[1])
					mobileno = code + mobileno
					
					sms_payload = {'topic': 'MyEZIoT' , 'msg' : msg, 'mobileno': mobileno}
					r = requests.post("https://myeziot.com/sms", data=sms_payload)
					print("SMS sent")
		

		
		query3 = "SELECT threshold2 , description FROM nodes WHERE gatewayno = '{0}' AND threshold2 IS NOT NULL".format(row) 
		cur.execute(query3)
		result2 = cur.fetchall()
		numrow2 = cur.rowcount
		
		if numrow2 > 0 :
			in_query2 = "SELECT 0 + column2, UNIX_TIMESTAMP(timestamp) FROM {0} WHERE column2 is NOT NULL".format(row)
			cur.execute(in_query2)
			in_result = cur.fetchall()
			in_numcount = cur.rowcount
			
			if in_numcount > 0:
				dataset2 = pd.read_sql(in_query2,con=db)
				array2 = dataset2.values
				X2 = array[:,1]
				Y2 = array[:,0]
				X2 = X2.reshape(-1,1)
				
				features2 = X2
				labels2 = Y2
				
				clf2 = tree.DecisionTreeRegressor()
				clf2 = clf2.fit(features2,labels2)
				
				timestamp = int(time.time() + 600)
				predict2 = clf.predict([[timestamp]])
				
				for threshold in result2 :
					thres2 = float(threshold[0])
					desc2 = threshold[1]
				
				time_result2 = 0
				time_query2 = "SELECT smsmtime FROM nodes WHERE gatewayno = '{0}' AND smsmtime is NOT NULL".format(row)
				cur.execute(time_query2)
				time_results2 = cur.fetchall()
				num_row2 = cur.rowcount
				
				if num_row2 > 0:
					for time_r2 in time_results2 :
						time_result2 = time_r2[0]
						
					time_result2 = int(time_result2)
				
				cur_time2 = time.time()
				cur_time_int2 = int(cur_time2) 
				difference2 = cur_time_int2 - time_result2
				
				if predict2 >= thres2 and diference2 >= 900:
					command_used = command[position]
					switch_used = switches[position]
					desc_used = description[position]
					payload = {'command': command_used , 'deviceID': switch_used }
					r = requests.get('http://www.myeziot.com/pages/netpie/netpie-put.php', params=payload)
					print("Triggered Thres2 " + str(command_used) + " " + str(switch_used))
					update_time = str(cur_time_int2)
					sms_query2 = 'UPDATE nodes SET smsmtime = " {0} " WHERE gatewayno = " {1} " '.format(update_time,row)
					cur.execute(sms_query2)
					
					time_2 = str('{:%Y-%m-%d %H:%M:%S}'.format(datetime.datetime.now()))
					
					msg = "" + str(desc_used) + " SET TO " + str(command_used) + " as predicted value of " + str(predict2) + " exceed threshold of " + str(thres2) +  " for " + str(desc2) + " Last updated " + time_2; 
					
					switch_user = username[position]
					num_query = "SELECT mobileno , callingcode FROM fyp WHERE username = '{0}' ".format(switch_user)
					cur.execute(num_query)
					for mob in cur.fetchall():
							mobileno = str(mob[0])  
							code = str(mob[1])
					mobileno = code + mobileno
					sms_payload = {'topic': 'MyEZIoT' , 'msg': msg, 'mobileno': mobileno}
					r = requests.post("https://myeziot.com/sms", data=sms_payload)
					print("SMS sent")
					
		query4 = "SELECT threshold3 ,description FROM nodes WHERE gatewayno = '{0}' AND threshold3 IS NOT NULL".format(row) 
		cur.execute(query4)
		result3 = cur.fetchall()
		numrow3 = cur.rowcount
		
		if numrow3 > 0 :
			in_query3 = "SELECT 0 + column3, UNIX_TIMESTAMP(timestamp) FROM {0} WHERE column3 is NOT NULL".format(row)
			cur.execute(in_query3)
			in_result = cur.fetchall()
			in_numcount = cur.rowcount
			
			if in_numcount > 0:
				dataset3 = pd.read_sql(in_query3,con=db)
				array3 = dataset3.values
				X3 = array[:,1]
				Y3 = array[:,0]
				X3 = X3.reshape(-1,1)
				
				features3 = X3
				labels3 = Y3
				
				clf3 = tree.DecisionTreeRegressor()
				clf3 = clf3.fit(features3,labels3)
				
				timestamp = int(time.time() + 600)
				predict3 = clf3.predict([[timestamp]])
				
				for threshold in result3 :
					thres3 = float(threshold[0])
					desc3 = threshold[1]
					
				time_result3 = 0
				time_query3 = "SELECT smsmtime FROM nodes WHERE gatewayno = '{0}' AND smsmtime is NOT NULL".format(row)
				cur.execute(time_query3)
				time_results3 = cur.fetchall()
				num_row3 = cur.rowcount
				
				if num_row3 > 0:
					for time_r3 in time_results3 :
						time_result3 = time_r3[0]
						
					time_result3 = int(time_result3)
				
				cur_time3 = time.time()
				cur_time_int3 = int(cur_time3) 
				difference3 = cur_time_int3 - time_result3
				
				if predict3 >= thres3 and diference3 >= 900:
					command_used = command[position]
					switch_used = switches[position]
					desc_used = description[position]
					payload = {'command': command_used , 'deviceID': switch_used }
					r = requests.get('http://www.myeziot.com/pages/netpie/netpie-put.php', params=payload)
					print("Triggered Thres3" + " " + str(command_used) + " " + str(switch_used))
					update_time = str(cur_time_int3)
					sms_query3 = 'UPDATE nodes SET smsmtime = " {0} " WHERE gatewayno = " {1} " '.format(update_time,row)
					cur.execute(sms_query3)
					
					time_3 = str('{:%Y-%m-%d %H:%M:%S}'.format(datetime.datetime.now()))
					
					msg = "" + str(desc_used) + " SET TO " + str(command_used) + " as predicted value of " + str(predict3) + " exceed threshold of " + str(thres3) + " for " + str(desc3) + " Last updated " + time_3; 
					
					switch_user = username[position]
					num_query = "SELECT mobileno , callingcode FROM fyp WHERE username = '{0}' ".format(switch_user)
					cur.execute(num_query)
					for mob in cur.fetchall():
							mobileno = str(mob[0])  
							code = str(mob[1])
					mobileno = code + mobileno
					sms_payload = {'topic': 'MyEZIoT' , 'msg': msg, 'mobileno': mobileno}
					r = requests.post("https://myeziot.com/sms", data=sms_payload)
					print("SMS sent")
					
		query5 = "SELECT threshold4 ,description FROM nodes WHERE gatewayno = '{0}' AND threshold4 IS NOT NULL".format(row) 
		cur.execute(query5)
		result4 = cur.fetchall()
		numrow4 = cur.rowcount
		
		if numrow4 > 0 :
			in_query4 = "SELECT 0 + column4, UNIX_TIMESTAMP(timestamp) FROM {0} WHERE column4 is NOT NULL".format(row)
			cur.execute(in_query4)
			in_result = cur.fetchall()
			in_numcount = cur.rowcount
			
			if in_numcount > 0:
				dataset4 = pd.read_sql(in_query4,con=db)
				array4 = dataset4.values
				X4 = array[:,1]
				Y4 = array[:,0]
				X4 = X4.reshape(-1,1)
				
				features4 = X4
				labels4 = Y4
				
				clf4 = tree.DecisionTreeRegressor()
				clf4 = clf4.fit(features4,labels4)
				
				timestamp = int(time.time() + 600)
				predict4 = clf4.predict([[timestamp]])
				
				for threshold in result4 :
					thres4 = float(threshold[0])
					desc4 = threshold[1]
					
				time_result4 = 0
				time_query4 = "SELECT smsmtime FROM nodes WHERE gatewayno = '{0}' AND smsmtime is NOT NULL".format(row)
				cur.execute(time_query4)
				time_results4 = cur.fetchall()
				num_row4 = cur.rowcount
				
				if num_row4 > 0:
					for time_r4 in time_results4 :
						time_result4 = time_r4[0]
						
					time_result4 = int(time_result4)
				
				cur_time4 = time.time()
				cur_time_int4 = int(cur_time4) 
				difference4 = cur_time_int4 - time_result4
				
				if predict4 >= thres4 and diference4 >= 900:
					command_used = command[position]
					switch_used = switches[position]
					desc_used = description[position]
					payload = {'command': command_used , 'deviceID': switch_used }
					r = requests.get('http://www.myeziot.com/pages/netpie/netpie-put.php', params=payload)
					print("Triggered Thres4" + " " + str(command_used) + " " + str(switch_used))
					update_time = str(cur_time_int4)
					sms_query4 = 'UPDATE nodes SET smsmtime = " {0} " WHERE gatewayno = " {1} " '.format(update_time,row)
					cur.execute(sms_query4)
					
					time_4 = str('{:%Y-%m-%d %H:%M:%S}'.format(datetime.datetime.now()))
					
					msg = "" + str(desc_used) + " SET TO " + str(command_used) + " as predicted value of " + str(predict4) + " exceed threshold of " + str(thres4) + " for " + str(desc4) + " Last updated " + time_4; 
					
					switch_user = username[position]
					num_query = "SELECT mobileno , callingcode FROM fyp WHERE username = '{0}' ".format(switch_user)
					cur.execute(num_query)
					for mob in cur.fetchall():
							mobileno = str(mob[0])  
							code = str(mob[1])
					mobileno = code + mobileno
					sms_payload = {'topic': 'MyEZIoT' , 'msg': msg, 'mobileno': mobileno}
					r = requests.post("https://myeziot.com/sms", data=sms_payload)
					print("SMS sent")
			   
	db.close()
	sleep(60)

