from pony.orm import *
from configobj import ConfigObj

dbConfig = ConfigObj('/usr/local/nlpanel/etc/sql.conf')
db = Database('sqlite', dbConfig['database'])

class Application(db.Entity):
    _table_='new_users'
    username=Required(str)
    fname=Required(str)
    lname=Required(str)
    pname=Required(str)
    bday=Required(str)
    syear=Required(str)
    primary_group=Required(str)
    phone=Optional(str)
    email=Optional(str)

db.generate_mapping()

@db_session
def main():
    str = select(a for a in Application).show()
    print str

main()
