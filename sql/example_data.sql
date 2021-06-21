
--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `waitingInterval`, `meetInterval`, `start`, `userId`, `currentTime`, `state`, `activated`) VALUES
(60, 'Небесна механика', 1, 10, '2021-06-30 06:30:00', 40, NULL, 0, 0),
(61, 'Реторика', 1, 5, '2021-06-20 23:10:42', 40, '2021-06-20 23:10:42', 0, 0),
(62, 'Дерматология', 5, 20, '2021-06-25 11:00:00', 40, NULL, 0, 0);

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `userId`, `roomId`, `place`) VALUES
(29, 35, 60, 1),
(30, 36, 60, 2),
(31, 37, 60, 3),
(32, 38, 60, 4),
(33, 34, 60, 5),
(34, 34, 61, 1),
(35, 36, 61, 2),
(36, 38, 61, 3),
(37, 35, 61, 4),
(38, 36, 62, 1),
(39, 38, 62, 2),
(40, 37, 62, 3),
(41, 37, 61, 5);

--
-- Dumping data for table `students_details`
--

INSERT INTO `students_details` (`id`, `fn`, `degree`, `year`, `userId`) VALUES
(14, 62222, 'СИ', '3', 34),
(15, 63333, 'СИ', '3', 35),
(16, 61111, 'СИ', '3', 36),
(17, 64444, 'СИ', '3', 37),
(18, 65555, 'СИ', '3', 38);


--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `userTypeId`) VALUES
(34, 'Николай Георгиев', '$2y$10$fCrEkKlqgpMEd5OCztCM1OyY7bW5dCzbtCSu2m9qczm.44uL4odxW', 'ng@example.com', 1),
(35, 'Ерик Здравков', '$2y$10$jnKn0AylQRLPN9OPTFTD8.D51s7.sy2kmkvIz9MlEj6JkJrq4X1sm', 'ez@example.com', 1),
(36, 'Никол Георгиева', '$2y$10$BC56gF2bz5SfVZbbcypizu/gRoRMKLi4d6o1FR1H/t8LtFPuif3Yi', 'nga@example.com', 1),
(37, 'Ерика Здравкова', '$2y$10$CVkg45dyNrYxHn8/EJuOce1yyyHbwqQEgh35mESQnSqNQeL6G1Gzy', 'eza@example.com', 1),
(38, 'Студент Студентски', '$2y$10$BHCoFR8/HygKQk0dF7aY9uelcTMSGn8qzHW.dsiYoTRdD2AnM0a2a', 'ss@example.com', 1),
(39, 'Преподавател Преподавателски', '$2y$10$2f2/JJ3/uJk7NdCpnZQehebJdtioe9XwQGzubRY/VtQ85Csq367R2', 'pp@example.com', 2),
(40, 'Мастър Йода', '$2y$10$WXHFH6qA5grYxVXjr7YVEu.MVWqmBGLkk064Qcy6ip7BgLReVEZGS', 'ioda@example.com', 2);

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`id`, `name`, `code`) VALUES
(1, 'Студент', 'STUDENT'),
(2, 'Преподавател', 'TEACHER');

--
-- Dumping data for table `queues`
--

INSERT INTO `queues` (`userIndex`, `userId`, `roomId`, `active`) VALUES
(2, 36, 61, 0),
(1, 34, 61, 0),
(4, 35, 61, 0),
(3, 38, 61, 0);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `queues`
--
ALTER TABLE `queues`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `students_details`
--
ALTER TABLE `students_details`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `usertypes`
--
ALTER TABLE `usertypes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;