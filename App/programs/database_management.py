import os
import pickle
import shutil

"""
I will start with a database in used pickle.

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
For every radio button, it returns a number between 0 and the number of options -1
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
-------------------------------------------------------------------------------
DATATEST:
###____BASE.PKL____###
{
'13~Clairville~35510': {'id': 5000
                        'Time of Day': 15
                        'House Size': 2, 
                        'Alarm or Microphone': False, 
                        'Dog': 0, 
                        'Age' 3, 
                        'Order Amount': 4
                        },
'64~Fontenelle~35510': {'id': 5001
                        'Time of Day': 15
                        'House Size': 3, 
                        'Alarm or Microphone': True, 
                        'Dog': 1, 
                        'Age' 2, 
                        'Order Amount': 0
                        },
'20~Fontenelle~35510': {'id': 5002
                        'Time of Day': 16
                        'House Size': 1, 
                        'Alarm or Microphone': True, 
                        'Dog': 1, 
                        'Age' 1, 
                        'Order Amount': 4
                        },
}

###____USER.PKL____###
{
'Nikitas~Giakkoupis': [5000], 
'Max~DECKMYN': [5001, 5002]
}
"""


#  ###_____________________________CUSTOM EXCEPTION :_____________________________###  #
class ThisUserAlreadyExists(Exception):
    def __init__(self, username):
        super().__init__(
            f'The user ({username}) you want to add already exists and you cannot have the same user twice.')


class UserNotFoundError(Exception):
    def __init__(self, username):
        super().__init__(f'The user "{username}" does not exist.')


class ArgumentFormatError(Exception):
    """Exception raised when an argument does not match the required format."""

    def __init__(self, arg_name):
        self.arg_name = arg_name
        super().__init__(f"The argument '{arg_name}' has an invalid format. "
                         f"It should be in lowercase with the first letter capitalized, "
                         f"separated by '~' between first name and name (in uppercase).")


DATATEST_BASE = {
    '13~Rue~de Clairville~35510': {'id': 1,
                                   'Time of Day': 15,
                                   'House Size': 2,
                                   'Alarm or Microphone': False,
                                   'Dog': 0,
                                   'Age': 3,
                                   'Order Amount': 4
                                   },
    '64~Avenue~de la Grande Fontenelle~35510': {'id': 2,
                                                'Time of Day': 15,
                                                'House Size': 3,
                                                'Alarm or Microphone': True,
                                                'Dog': 1,
                                                'Age': 2,
                                                'Order Amount': 0
                                                },
    '20~Avenue~de la Grande Fontenelle~35510': {'id': 3,
                                                'Time of Day': 16,
                                                'House Size': 1,
                                                'Alarm or Microphone': True,
                                                'Dog': 1,
                                                'Age': 1,
                                                'Order Amount': 4
                                                },
}
DATATEST_USER = {
    'Pierre~DUPOND': [2],
    'Jean~DUPONT': [1, 3]}


class DataBase:
    working_path = os.getcwd()
    extension_files = '.pkl'

    def __init__(self, reboot=False, test_mode=False):
        self.test_mode = test_mode
        if reboot:
            self.clear()
        self.create_files()

        self.main_data = {}
        self.user_data = {}
        self.update_data()

    def clear(self):
        if os.path.isdir(f'{self.working_path}/data'):
            shutil.rmtree(f'{self.working_path}/data')
            return 'data was deleted'

    def create_files(self):
        if not os.path.isdir(f'{self.working_path}/data'):
            os.mkdir(f'{self.working_path}/data')
        if not os.path.isfile(f'{self.working_path}/data/main{self.extension_files}'):
            with open(f'{self.working_path}/data/main{self.extension_files}', 'wb') as file:
                if self.test_mode:
                    file.write(pickle.dumps(DATATEST_BASE))
                else:
                    file.write(pickle.dumps('{}'))
                file.close()
        if not os.path.isfile(f'{self.working_path}/data/user{self.extension_files}'):
            with open(f'{self.working_path}/data/user{self.extension_files}', 'wb') as file:
                if self.test_mode:
                    file.write(pickle.dumps(DATATEST_USER))
                else:
                    file.write(pickle.dumps('{}'))
                file.close()

    def update_data(self):
        with open(f'{self.working_path}/data/main{self.extension_files}', 'rb') as file:
            self.main_data = file.read()
            self.main_data = pickle.loads(self.main_data)
            file.close()
        with open(f'{self.working_path}/data/user{self.extension_files}', 'rb') as file:
            self.user_data = file.read()
            self.user_data = pickle.loads(self.user_data)
            file.close()

    def check_user_exist(self, username):
        with open(f'{self.working_path}/data/user{self.extension_files}', 'rb') as file:
            users = pickle.loads(file.read())
            if username in users:
                return True
            else:
                return False

    def load_data(self, datafile_name):
        with open(f'{self.working_path}/data/{datafile_name}{self.extension_files}', 'rb') as file:
            data = pickle.loads(file.read())
            file.close()
            return data

    def save_data(self):
        with open(f'{self.working_path}/data/main{self.extension_files}', 'wb') as file:
            file.write(pickle.dumps(self.main_data))
            file.close()
        with open(f'{self.working_path}/data/user{self.extension_files}', 'wb') as file:
            file.write(pickle.dumps(self.user_data))
            file.close()

    def add_new_user(self, username: str):
        try:
            surname, lastname = username.split('~')
        except ValueError:
            raise ArgumentFormatError(username)
        if not lastname.isupper():
            raise ArgumentFormatError(username)
        elif not surname.istitle():
            raise ArgumentFormatError(username)

        if not self.check_user_exist(username):
            if username not in self.user_data:
                self.user_data[username] = []
                self.save_data()
            else:
                raise ThisUserAlreadyExists(username)
        else:
            raise ThisUserAlreadyExists(username)

    def add_new_house_data(self, username, address, time, size, security, dog, age, amount):
        if not self.check_user_exist(username):
            raise ThisUserAlreadyExists(username)
        else:
            house_id = self.generate_id()
            self.main_data[address] = {'id': house_id,
                                       'Time of Day': int(time),
                                       'House Size': size,
                                       'Alarm or Microphone': security,
                                       'Dog': dog,
                                       'Age': age,
                                       'Order Amount': amount
                                       }
            self.user_data[username].append(house_id)
        self.save_data()

    def generate_id(self):
        #  This function will return a valid and unique ID to house
        id_max = 0
        for house_address in self.main_data.keys():
            id_max = max(int(self.main_data[house_address]['id']), id_max)
        id_max += 1
        return id_max
