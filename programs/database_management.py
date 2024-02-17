import os
import sqlite3

"""
####________________________________FORM :________________________________####

House Address: [Insert address]
Time of Day: [Insert time]

Did the person respond? : [ ] Yes [ ] No

    Estimated House Size: [ ] Small [ ] Medium [ ] Large
    Big Security Gate or Alarm: [ ] Yes [ ] No
    Security : [ ] Yes [ ] No
    Presence of Dog: [ ] Unknown [ ] Small [ ] Large [ ] None
    Estimated Age of Person: [ ] Nobody answered [ ] Young [ ] Middle-aged [ ] Elderly
    Gender of Person: [ ] Male [ ] Female
    Order Amount: [Insert amount]

Gender : 
[ ] Male --> 1
[ ] Female --> 2


####________________________________DATA :________________________________####

-------------------------------------------------------------------------------
For every radio button, it returns a number between 0 and the number of options
-------------------------------------------------------------------------------

DataBase:
{f'[House Address]': {'id': int #  Not in the training data, just to have a better organisation 
                      'Time of Day': int
                      'House Size': int, 
                      'Alarm or Microphone': bool, 
                      'Dog': int, 
                      'Age' int, 
                      'Order Amount': int
                      },
}

Person : 
{'[name]': list -> List with all House's ID of someone,
}
"""


class DataBase:
    def __init__(self):
        self.connection = sqlite3.connect('../data/training_data.db')
        self.cursor = self.connection.cursor()

    def new_user(self, user_name):
        pass


class Person:
    def __init__(self):
        pass


if __name__ == "__main__":
    db = DataBase()
