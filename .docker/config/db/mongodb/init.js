
use admindb;
db.createUser(
  {
    user: "admin",
    pwd: "admin", 
    roles: [ { role: "adminRole", db: "my_admin" }, "readWriteAnyDatabase" ]
  }
);

use userdb;
db.createUser({
    user: "user",
    pwd: "password",
    roles: [{ role: "readWrite", db: "my_db" }, { role: "dbAdmin", db: "my_db"}]
});
