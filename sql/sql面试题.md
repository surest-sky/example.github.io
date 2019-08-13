SQL 必会50道面试题

学生表：
Student(s_id,s_name,s_birth,s_sex) –学生编号,学生姓名, 出生年月,学生性别
课程表：
Course(c_id,c_name,t_id) – –课程编号, 课程名称, 教师编号
教师表：
Teacher(t_id,t_name) –教师编号,教师姓名
成绩表：
Score(s_id,c_id,s_s_score) –学生编号,课程编号,分数

根据以上信息按照下面要求写出对应的SQL语句。
ps：这些题考察SQL的编写能力，对于这类型的题目，需要你先把4张表之间的关联关系搞清楚了，最好的办法是自己在草稿纸上画关联图，然后再编写对应的SQL语句就比较容易了。下图是我在草稿纸上画的这4张表的关系图，不好理解，你可以列举一些数据案例来辅助理解：



案例数据建立参考如下
表名和字段
–1.学生表 

Student(s_id,s_name,s_birth,s_sex) –学生编号,学生姓名, 出生年月,学生性别 

–2.课程表 

Course(c_id,c_name,t_id) – –课程编号, 课程名称, 教师编号 

–3.教师表 

Teacher(t_id,t_name) –教师编号,教师姓名 

–4.成绩表 

Score(s_id,c_id,s_score) –学生编号,课程编号,分数
测试数据
--建表
--学生表
CREATE TABLE `Student`(
`s_id` VARCHAR(20),
`s_name` VARCHAR(20) NOT NULL DEFAULT '',
`s_birth` VARCHAR(20) NOT NULL DEFAULT '',
`s_sex` VARCHAR(10) NOT NULL DEFAULT '',
PRIMARY KEY(`s_id`)
);
--课程表
CREATE TABLE `Course`(
`c_id` VARCHAR(20),
`c_name` VARCHAR(20) NOT NULL DEFAULT '',
`t_id` VARCHAR(20) NOT NULL,
PRIMARY KEY(`c_id`)
);
--教师表
CREATE TABLE `Teacher`(
`t_id` VARCHAR(20),
`t_name` VARCHAR(20) NOT NULL DEFAULT '',
PRIMARY KEY(`t_id`)
);
--成绩表
CREATE TABLE `Score`(
`s_id` VARCHAR(20),
`c_id` VARCHAR(20),
`s_score` INT(3),
PRIMARY KEY(`s_id`,`c_id`)
);
--插入学生表测试数据
insert into Student values('01' , '赵雷' , '1990-01-01' , '男');
insert into Student values('02' , '钱电' , '1990-12-21' , '男');
insert into Student values('03' , '孙风' , '1990-05-20' , '男');
insert into Student values('04' , '李云' , '1990-08-06' , '男');
insert into Student values('05' , '周梅' , '1991-12-01' , '女');
insert into Student values('06' , '吴兰' , '1992-03-01' , '女');
insert into Student values('07' , '郑竹' , '1989-07-01' , '女');
insert into Student values('08' , '王菊' , '1990-01-20' , '女');
--课程表测试数据
insert into Course values('01' , '语文' , '02');
insert into Course values('02' , '数学' , '01');
insert into Course values('03' , '英语' , '03');

--教师表测试数据
insert into Teacher values('01' , '张三');
insert into Teacher values('02' , '李四');
insert into Teacher values('03' , '王五');

--成绩表测试数据
insert into Score values('01' , '01' , 80);
insert into Score values('01' , '02' , 90);
insert into Score values('01' , '03' , 99);
insert into Score values('02' , '01' , 70);
insert into Score values('02' , '02' , 60);
insert into Score values('02' , '03' , 80);
insert into Score values('03' , '01' , 80);
insert into Score values('03' , '02' , 80);
insert into Score values('03' , '03' , 80);
insert into Score values('04' , '01' , 50);
insert into Score values('04' , '02' , 30);
insert into Score values('04' , '03' , 20);
insert into Score values('05' , '01' , 76);
insert into Score values('05' , '02' , 87);
insert into Score values('06' , '01' , 31);
insert into Score values('06' , '03' , 34);
insert into Score values('07' , '02' , 89);
insert into Score values('07' , '03' , 98);


## 第一题

1.查询课程编号为“01”的课程比“02”的课程成绩高的所有学生的学号（重点）


    # 方法 1
    SELECT
        st.*, a.s_score,
        b.s_score
    FROM
        Student AS st
        INNER JOIN (SELECT * FROM Score where c_id = '01') as a on st.s_id = a.s_id 
        INNER JOIN (SELECT * FROM Score where c_id = '02') as b on a.s_id = b.s_id 
    where a.s_score > b.s_score

    #  方法2
    select a.s_id,a.s_score from 
    (select * from Score where c_id = "01") as a
    left join
    (select * FROM Score where c_id = "02") as b
    on a.s_id = b.s_id
    where a.s_score > b.s_score

结果 

![图片描述...](http://cdn.surest.cn/Fo3IBn_Ede8goQtDXPJy_eP2ouZY)

2. 查询平均成绩大于60分的学生的学号和平均成绩（简单，第二道重点）

    SELECT avg(s_score), s_id FROM Score GROUP BY s_id HAVING avg(s_score) > 60

![图片描述...](http://cdn.surest.cn/FokL6duDq2oyUVZAaTXXQlF5YHqs)


3. 查询所有学生的学号、姓名、选课数、总成绩（不重要）

    SELECT
        a.s_id,
        a.s_name,
        count(b.s_id),
        sum(b.s_id)
    FROM
        Student a
    JOIN Score b ON b.s_id = a.s_id
    GROUP BY
        a.s_id

![图片描述...](http://cdn.surest.cn/Fk6wEHSieMwXQ-Iz51fMriIWs7dx)

4. 查询姓“猴”的老师的个数（不重要）

    SELECT count(*) from Teacher where t_name  like "猴%"

5. 查询没学过“张三”老师课的学生的学号、姓名（重点）

![图片描述...](http://cdn.surest.cn/FoBexVArLWx0fuP43yZznLqHxiny)

    SELECT s_id, s_name FROM Student
    where s_id not in (SELECT sc.s_id from Score as sc 
    INNER JOIN (SELECT * from Course) as c on c.c_id = sc.c_id
    INNER JOIN (SELECT * FROM Teacher) as te on c.t_id = te.t_id
    WHERE te.t_name = "张三")

6. 查询学过编号为“01”的课程并且也学过编号为“02”的课程的学生的学号、姓名（重点）

![图片描述...](http://cdn.surest.cn/FuqxczRLDil10_aSHa2bALv7ETJf)

    select * from Student where s_id in (
    SELECT b.s_id from (SELECT * from Score where c_id = "01") as a
    inner JOIN (select * from Score where c_id = "02") as b
    ON a.s_id = b.s_id )

9. 查询所有课程成绩小于60分的学生的学号、姓名

    SELECT * from Student  where s_id in (
        SELECT s_id FROM Score AS a
        WHERE 60>ALL(SELECT s_score FROM Score AS b
        WHERE a.s_id=b.s_id)
    )

10. 