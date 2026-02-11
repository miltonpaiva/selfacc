-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 11/02/2026 às 16:25
-- Versão do servidor: 11.8.3-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `product` (`p_id`, `p_name`, `p_price`, `p_description`, `p_image`, `p_sv_category_pd_fk`, `p_dt_created`, `p_dt_updated`) VALUES
(1, 'Água sem gás', 3.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 08:34:46'),
(2, 'Água com gás', 4.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 06:54:02'),
(3, 'Água de côco', 5.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 06:54:02'),
(4, 'Refrigerante lata (Coca)', 6.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 06:54:02'),
(5, 'Refrigerante 1 litro (Coca)', 10.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 06:54:02'),
(6, 'Refrigerante 1 litro (Guaraná)', 10.00, ' ', NULL, 20, '2025-10-18 06:54:02', '2025-10-18 06:54:02'),
(7, 'Bolinha de queijo', 18.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(8, 'Bolinha de carne de sol', 18.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(9, 'Pastel de queijo', 12.00, ' 12 unidades', NULL, 24, '2025-10-18 08:36:18', '2025-10-19 15:09:31'),
(10, 'Pastel de carne de sol', 12.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(11, 'Pastel de peixe', 12.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(12, 'Camarão ao alho e óleo', 32.00, ' 500 gramas de um delicioso camarão ', NULL, 23, '2025-10-18 08:37:43', '2025-11-16 21:13:32'),
(13, 'Calabresa acebolada', 16.00, ' ', NULL, 23, '2025-10-18 08:37:43', '2025-10-18 08:37:43'),
(14, 'Peixe Cará/Tilápia', 85.00, ' ', NULL, 23, '2025-10-18 08:37:43', '2025-10-18 08:37:43'),
(15, 'Arroz branco', 9.00, ' ', NULL, 26, '2025-10-18 08:37:43', '2025-10-18 08:37:43'),
(16, 'Baião de Dois', 9.00, ' ', NULL, 26, '2025-10-18 08:37:43', '2025-10-18 08:37:43'),
(17, 'Skol	', 13.00, ' ', NULL, 21, '2025-10-18 08:39:02', '2025-10-18 08:39:02'),
(18, 'Budweiser ', 14.00, ' ', NULL, 21, '2025-10-18 08:39:02', '2025-10-18 08:39:02'),
(19, 'Skol	', 5.00, ' ', NULL, 22, '2025-10-18 08:39:02', '2025-10-18 08:39:02'),
(20, 'Brahma Duplo Malte', 6.00, ' ', NULL, 22, '2025-10-18 08:39:02', '2025-12-19 23:29:14'),
(21, 'Caipirinha', 13.00, ' ', NULL, 30, '2025-10-18 08:39:02', '2025-10-18 15:52:48'),
(22, 'Caipifruta', 16.00, ' ', NULL, 30, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(23, 'Cigarro unidade', 1.00, ' ', NULL, 27, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(24, 'Cigarro carteira', 14.00, ' ', NULL, 27, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(25, 'Ypioca prata\\ouro', 6.00, ' ', NULL, 31, '2025-10-18 08:39:02', '2025-10-18 15:06:59'),
(26, '51 Mel e limao', 5.00, ' ', NULL, 31, '2025-10-18 08:39:02', '2025-10-18 15:06:59'),
(27, 'Montila', 5.00, ' ', NULL, 31, '2025-10-18 08:39:02', '2025-10-18 15:06:59'),
(28, 'Caipifruta sem álcool ', 12.00, ' ', NULL, 30, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(29, 'Caipirinha sem álcool ', 10.00, ' ', NULL, 30, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(30, 'Batata frita', 18.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(31, 'Ficha', 1.00, ' ', NULL, 27, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(32, 'Caldo', 7.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(33, 'Ice	', 11.00, ' ', NULL, 22, '2025-10-18 08:39:02', '2025-10-18 08:39:02'),
(34, 'Vinho são bras', 10.00, ' ', NULL, 32, '2025-10-18 08:39:02', '2025-12-06 17:41:49'),
(35, 'Heineken', 10.00, ' ', NULL, 22, '2025-10-18 08:39:02', '2025-10-18 08:39:02'),
(36, 'Carangueijo', 9.00, ' ', NULL, 23, '2025-10-18 08:37:43', '2025-10-18 08:37:43'),
(37, 'Copão de gin', 12.00, ' ', NULL, 30, '2025-10-18 08:39:02', '2025-12-14 18:11:12'),
(38, 'Combo batata + calabresa acebolada ', 24.00, ' ', NULL, 34, '2025-10-18 08:39:02', '2025-12-06 17:40:55'),
(39, 'Régua (8 unid)', 20.00, ' ', NULL, 31, '2025-10-18 08:39:02', '2025-11-07 23:11:49'),
(40, 'Kit seda + piteira/filtro', 0.50, ' ', NULL, 27, '2025-10-18 08:39:02', '2025-10-18 14:35:51'),
(41, 'Combo batata + calabresa acebolada + baião ', 38.00, ' ', NULL, 34, '2025-10-18 08:39:02', '2025-12-06 17:40:46'),
(42, 'Bolinha de peixe', 18.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(43, 'Bolinha de camarão ', 18.00, ' ', NULL, 24, '2025-10-18 08:36:18', '2025-10-18 08:36:18'),
(44, 'Combo 3 caranguejo ', 24.00, ' ', NULL, 34, '2025-10-18 08:39:02', '2025-12-06 17:40:40'),
(45, 'Vinho quinta do morgado ', 18.00, ' ', NULL, 32, '2025-10-18 08:39:02', '2025-12-06 17:39:52'),
(46, 'Espeto frango', 6.00, ' ', NULL, 33, '2025-10-18 08:39:02', '2025-12-06 17:39:46'),
(47, 'Espeto boi', 7.00, ' ', NULL, 33, '2025-10-18 08:39:02', '2025-12-06 17:39:37'),
(48, 'Espeto medalhão ', 8.00, ' ', NULL, 33, '2025-10-18 08:39:02', '2025-12-06 17:39:18'),
(49, 'Espeto porco', 6.00, ' ', NULL, 33, '2025-10-18 08:39:02', '2025-12-06 17:39:12'),
(50, 'Espeto coração ', 7.00, ' ', NULL, 33, '2025-10-18 08:39:02', '2025-12-06 17:39:07'),
(51, 'Pirulito POP', 1.00, ' ', NULL, 27, '2025-10-18 08:39:02', '2025-10-18 14:35:51');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `product_p_sv_category_pd_fk_foreign` (`p_sv_category_pd_fk`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `product`
--
ALTER TABLE `product`
  MODIFY `p_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_p_sv_category_pd_fk_foreign` FOREIGN KEY (`p_sv_category_pd_fk`) REFERENCES `simple_values` (`sv_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
