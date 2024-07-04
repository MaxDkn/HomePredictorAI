from database_management import PickleDataBase
import os

if __name__ == "__main__":
    pickle_data = PickleDataBase()
    print(pickle_data.get_training_data())

