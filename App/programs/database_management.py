import os
import pickle
import shutil
import csv

#  Custom Exception
class UserNotFoundError(Exception):
    def __init__(self, username):
        super().__init__(f'The user "{username}" does not exist.')


"""
i convert my csv database to pickle structure
-------------------------------------------------------------------------------
SQLDataBase:
HomeInformation => HouseID
                => SellerID
                => SellerName
                => HouseAddress
                => DayTime
                => Respond [Yes | No]
                => HouseSize
                => SecurityGateOrAlarm
                => Dog
                => Age
                => Gender
                => Price
Users => ID
      => Access
      => FirstConnection
      => LastConnection
      => FirstName
      => LastName
      => Password

"""


class PickleDataBase:
    working_path = os.getcwd()
    tables = ['HomeInformations', 'Users']

    def __init__(self, reboot=True):
        self.reboot = reboot
        self.data = self.convert_csv_to_pickle_data()
        self.training_data_list = self.get_training_data()
        self.training_data_by_key = {'DayTime': [], 'Respond': [], 'HouseSize': [], 'SecurityGateOrAlarm': [],
                                     'Dog': [], 'Age': [], 'Gender': [], 'Price': []}
        self.get_training_data_by_key()
        self.change_seller_name_format()

    def draw_data(self):
        for key in self.training_data_by_key.keys():
            print(key.rjust(20, '-'), ':', end=' [')
            for value in self.training_data_by_key[key]:
                print(f'{str(value).center(6)}', end='|')
            print(']')

    def convert_csv_to_pickle_data(self) -> dict:
        data = {}
        for table_name in self.tables:
            with open(f'/home/maxdeckmyn/Documents/Workspace/Website/HomePredictorAI/App/dataSQL/HomeInformations.csv', 'r') as file:
                data[table_name] = [row for row in csv.DictReader(file)]
        return data

    def get_training_data(self):
        """
        HomeInformation => HouseID       # This information will not appear in the algorithm
                        => SellerID      # This information will not appear in the algorithm
                        => SellerName    # This information will not appear in the algorithm
                        => HouseAddress  # This information will not appear in the algorithm
                        => DayTime => conversion of hour minute to just hour in decimal (ex: 16:30 = 16.5)
                        => Respond [No: 0 | Yes : 1]
                        => HouseSize [Small: 0 | Medium: 1 | Big: 2]
                        => SecurityGateOrAlarm [No: 0 | Yes: 1]
                        => Dog [No: 0 | Unknown: 0 | Small: 1 | Large: 2]
                        => Age [Young: 0 | Middle-aged: 1 | Elderly: 2]
                        => Gender [Female: 0 | Male: 1]
                        => Price
        """
        values = {'Respond': {'No': 0, 'Yes': 1},
                  'HouseSize': {'Small': 0, 'Medium': 1, 'Big': 2},
                  'SecurityGateOrAlarm': {'No': 0, 'Yes': 1},
                  'Dog': {'No': 0, 'Unknown': 0, 'Small': 1, 'Large': 2},
                  'Age': {'Young': 0, 'Middle-aged': 1, 'Elderly': 2, '': None},
                  'Gender': {'Female': 0, 'Male': 1, '': None}}
        data_to_return = []
        for house in self.data['HomeInformations']:
            data = {}
            hour, minute = house['DayTime'].split(' ')[1].split(':')
            data['DayTime'] = int(hour) + int(int(minute) / 6) / 10
            for key_word in values.keys():
                data[key_word] = values[key_word][house[key_word]]
            data['Price'] = house['Price']
            data_to_return.append(data)

        return data_to_return

    def get_training_data_by_key(self):
        """
        HomeInformation => HouseID       # This information will not appear in the algorithm
                        => SellerID      # This information will not appear in the algorithm
                        => SellerName    # This information will not appear in the algorithm
                        => HouseAddress  # This information will not appear in the algorithm
                        => DayTime => conversion of hour minute to just hour in decimal (ex: 16:30 = 16.5)
                        => Respond [No: 0 | Yes : 1]
                        => HouseSize [Small: 0 | Medium: 1 | Big: 2]
                        => SecurityGateOrAlarm [No: 0 | Yes: 1]
                        => Dog [No: 0 | Unknown: 0 | Small: 1 | Large: 2]
                        => Age [Young: 0 | Middle-aged: 1 | Elderly: 2]
                        => Gender [Female: 0 | Male: 1]
                        => Price
        """
        values = {'Respond': {'No': 0, 'Yes': 1},
                  'HouseSize': {'Small': 0, 'Medium': 1, 'Big': 2},
                  'SecurityGateOrAlarm': {'No': 0, 'Yes': 1},
                  'Dog': {'No': 0, 'Unknown': 0, 'Small': 1, 'Large': 2},
                  'Age': {'Young': 0, 'Middle-aged': 1, 'Elderly': 2, '': None},
                  'Gender': {'Female': 0, 'Male': 1, '': None}}
        for house in self.data['HomeInformations']:
            data = {}
            hour, minute = house['DayTime'].split(' ')[1].split(':')
            self.training_data_by_key['DayTime'].append(int(hour) + int(int(minute) / 6) / 10)
            for key_word in values.keys():
                self.training_data_by_key[key_word].append(values[key_word][house[key_word]])
            self.training_data_by_key['Price'].append(int(house['Price']))

    def change_seller_name_format(self) -> None:
        """
        for the moment, the seller name is like this : 'LASTNAME Firstname' and we can have a confusion if the LASTNAME
        comport space character, so I will change this format to do this :  'LASTNAME~Firstname'
        this problem is just on the HomeInformations table in the SellerName key.
        """
        for row in self.data['HomeInformations']:
            if len(row['SellerName'].split(' ')) == 2:
                lastname, firstname = row['SellerName'].split(' ')
                row['SellerName'] = lastname.upper() + "~" + firstname.title()

    def get_house_by_name(self, lastname, firstname) -> list:
        access = False
        user = []
        for user in self.data['Users']:
            if user['LastName'] == lastname and user['FirstName'] == firstname:
                access = True
                break
        if not access:
            raise UserNotFoundError(f'{lastname} {firstname}')
        house_list = []
        for house in self.data['HomeInformations']:
            if house['SellerID'] == user['ID']:
                house_list.append(house)
        return house_list


class DataBase:
    working_path = os.getcwd()
    extension_files = '.pkl'

    def __init__(self, reboot=False):
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
                file.write(pickle.dumps('{}'))
                file.close()
        if not os.path.isfile(f'{self.working_path}/data/user{self.extension_files}'):
            with open(f'{self.working_path}/data/user{self.extension_files}', 'wb') as file:
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
