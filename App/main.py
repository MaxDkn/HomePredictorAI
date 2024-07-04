import os
import csv
from programs import database_management
import sklearn

import shap
"""
Script made by Max 10 / 02 / 2024
"""


if __name__ == '__main__':
    database_management.PickleDataBase().draw_data()
