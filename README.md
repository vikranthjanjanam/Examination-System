# Examination-System

The objective is then to develop a fully functional system to allow students to explore different types of exams on different courses. This system is implemented through a website and a server. 

This application provides an alternative means of grading students and maintaining the records of enrolled administrators and students. Major benefit is automation of results upon the completion of examination, reducing the time factor.
	
All the results, questions, student and administrator details are stored in the database. Merit of students will be secured by preventing the human interference during the evaluation.Every exam will be conducted within a specific duration. If the elapsed time is finished, then the examination will be aborted, thereby maintaining equality.

## Highlights

1. Interactive UI based on division hiding developed within a single webpage. 
2. Used XML Parser in PHP for questions representation and validation. 
3. User authentication will be verified via OTP sent to Mail.
4. Instead of depositing the exam details directly in the database table, exam id, questions, and answersare encoded in XML format and stored in the DataBase table with exam id as the primary key. This reduced the number of entries into the table and the load on the server while retrieving questions for the test taker, consequently increasing the efficiency. 
