-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 17, 2019 at 10:06 AM
-- Server version: 5.7.26-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_chaves`
--
CREATE DATABASE IF NOT EXISTS `chaves` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sistema_chaves`;

-- --------------------------------------------------------

--
-- Table structure for table `chave`
--

CREATE TABLE `chave` (
  `id_chave` int(11) NOT NULL,
  `descricao` varchar(7) NOT NULL,
  `ativo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL,
  `chave_id` int(11) NOT NULL,
  `data_retirada` datetime NOT NULL,
  `pessoa_id_retirada` int(11) NOT NULL,
  `data_devolucao` datetime DEFAULT NULL,
  `pessoa_id_devolucao` int(11) DEFAULT NULL,
  `observacao` text,
  `operador_retirada` int(11) NOT NULL,
  `operador_devolucao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pessoa`
--

CREATE TABLE `pessoa` (
  `id_pessoa` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `doc_identificacao` varchar(15) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `senha` varchar(72) NOT NULL,
  `perfil` tinyint(1) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pessoas_autorizadas`
--

CREATE TABLE `pessoas_autorizadas` (
  `chave_id_chave` int(11) NOT NULL,
  `pessoa_id_pessoa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chave`
--
ALTER TABLE `chave`
  ADD PRIMARY KEY (`id_chave`);

--
-- Indexes for table `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emprestimo_chave_idx` (`chave_id`),
  ADD KEY `fk_emprestimo_pessoa1_idx` (`pessoa_id_retirada`),
  ADD KEY `fk_emprestimo_pessoa2_idx` (`pessoa_id_devolucao`),
  ADD KEY `fk_emprestimo_pessoa3_idx` (`operador_retirada`),
  ADD KEY `fk_emprestimo_pessoa4_idx` (`operador_devolucao`);

--
-- Indexes for table `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id_pessoa`);
  ADD UNIQUE(`doc_identificacao`);

--
-- Indexes for table `pessoas_autorizadas`
--
ALTER TABLE `pessoas_autorizadas`
  ADD PRIMARY KEY (`chave_id_chave`,`pessoa_id_pessoa`),
  ADD KEY `fk_chave_has_pessoa_pessoa1_idx` (`pessoa_id_pessoa`),
  ADD KEY `fk_chave_has_pessoa_chave1_idx` (`chave_id_chave`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chave`
--
ALTER TABLE `chave`
  MODIFY `id_chave` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id_pessoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_emprestimo_chave` FOREIGN KEY (`chave_id`) REFERENCES `chave` (`id_chave`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_emprestimo_pessoa1` FOREIGN KEY (`pessoa_id_retirada`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_emprestimo_pessoa2` FOREIGN KEY (`pessoa_id_devolucao`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_emprestimo_pessoa3` FOREIGN KEY (`operador_retirada`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_emprestimo_pessoa4` FOREIGN KEY (`operador_devolucao`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pessoas_autorizadas`
--
ALTER TABLE `pessoas_autorizadas`
  ADD CONSTRAINT `fk_chave_has_pessoa_chave1` FOREIGN KEY (`chave_id_chave`) REFERENCES `chave` (`id_chave`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chave_has_pessoa_pessoa1` FOREIGN KEY (`pessoa_id_pessoa`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
