-- Coach Table Data
INSERT INTO COACH (COACH_FNAME, COACH_LNAME, EMAIL, PHONE_NUMBER, GENDER, SPECIALIZATION, IS_ACTIVE) VALUES
-- Strength Training Coaches
('Michael', 'Johnson', 'michael.johnson@gymaster.com', '+63 919 123 4567', 'MALE', 'Weightlifting, Bodybuilding, Strength & Conditioning', 1),
('Jessica', 'Parker', 'jessica.parker@gymaster.com', '+63 927 234 5678', 'FEMALE', 'Powerlifting, Olympic Lifting, Functional Training', 1),

-- Cardio Coaches
('Robert', 'Brown', 'robert.brown@gymaster.com', '+63 939 345 6789', 'MALE', 'Running, HIIT, Endurance Training', 1),
('Sarah', 'Williams', 'sarah.williams@gymaster.com', '+63 928 456 7890', 'FEMALE', 'Aerobics, Zumba, Spinning', 1),

-- Yoga Coaches
('Emma', 'Davis', 'emma.davis@gymaster.com', '+63 917 567 8901', 'FEMALE', 'Hatha Yoga, Meditation, Flexibility', 1),
('David', 'Wilson', 'david.wilson@gymaster.com', '+63 926 678 9012', 'MALE', 'Vinyasa Flow, Power Yoga, Mobility', 1),

-- CrossFit Coaches
('James', 'Taylor', 'james.taylor@gymaster.com', '+63 918 789 0123', 'MALE', 'CrossFit, Circuit Training, Tabata', 1),
('Lisa', 'Martinez', 'lisa.martinez@gymaster.com', '+63 929 890 1234', 'FEMALE', 'CrossFit, Calisthenics, AMRAP Training', 1);

-- Program-Coach Associations
-- Strength Training (ID: 1) - Michael (ID: 1), Jessica (ID: 2)
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (1, 1);
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (1, 2);

-- Cardio (ID: 2) - Robert (ID: 3), Sarah (ID: 4)
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (2, 3);
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (2, 4);

-- Yoga (ID: 3) - Emma (ID: 5), David (ID: 6)
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (3, 5);
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (3, 6);

-- CrossFit (ID: 4) - James (ID: 7), Lisa (ID: 8)
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (4, 7);
INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (4, 8);