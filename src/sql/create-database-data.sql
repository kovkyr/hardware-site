﻿begin;

delete from users;
insert into users ("user", "password") values ('admin', 'e10adc3949ba59abbe56e057f20f883e');

commit;
