# copy_cat
Allows an entire database's structure to be distilled to csv format, to be recreated with faked data.

##Installation
Download a fork of the repo as a zip. Extract the contents to a folder from which the program can be run.

##Usage
Either open a command line interface from the copy_cat directory, or open a command line interface and navigate to the copy_cat directory

Type "php copy_cat.php"

Enter the MySQL login credentials for the user.

You will be presented with a list of zero indexed available databases, enter the index of the database you wish to distill.

copy_cat will write the structure of that database to a csv named database_name.csv within the copy_cat directory.
