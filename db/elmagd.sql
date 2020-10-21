-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2020 at 12:06 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elmagd`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients_collectible`
--

CREATE TABLE `clients_collectible` (
  `collectible_sn` int(11) NOT NULL,
  `collectible_client_id` int(11) NOT NULL,
  `collectible_date` date NOT NULL,
  `collectible_type` enum('cash','cheque') NOT NULL,
  `collectible_value` decimal(11,2) NOT NULL,
  `collectible_cheque_date` date NOT NULL,
  `collectible_cheque_number` varchar(50) NOT NULL,
  `collectible_insert_in` enum('safe','bank') NOT NULL,
  `collectible_bank_id` int(2) NOT NULL,
  `collectible_account_type` enum('current','credit','saving') NOT NULL,
  `collectible_account_id` int(11) NOT NULL,
  `collectible_operations` text NOT NULL,
  `collectible_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clients_collectible_operations`
--

CREATE TABLE `clients_collectible_operations` (
  `id` int(11) NOT NULL,
  `collectible_id` int(11) NOT NULL,
  `operations_id` int(11) NOT NULL,
  `value` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clients_finance`
--

CREATE TABLE `clients_finance` (
  `clients_finance_sn` int(11) NOT NULL,
  `clients_finance_client_id` int(11) NOT NULL,
  `clients_finance_credit` decimal(11,2) NOT NULL,
  `clients_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients_finance`
--

INSERT INTO `clients_finance` (`clients_finance_sn`, `clients_finance_client_id`, `clients_finance_credit`, `clients_status`) VALUES
(1, 1, '40000.00', 1),
(2, 2, '20000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clients_pricing`
--

CREATE TABLE `clients_pricing` (
  `pricing_sn` int(11) NOT NULL,
  `pricing_product_rate` int(11) NOT NULL,
  `pricing_start_date` date NOT NULL,
  `pricing_end_date` date NOT NULL,
  `pricing_selling_price` decimal(5,2) NOT NULL,
  `pricing_supply_price` decimal(5,2) NOT NULL,
  `pricing_supply_percent` decimal(5,2) NOT NULL,
  `pricing_excuse_active` enum('on','off') NOT NULL,
  `pricing_excuse_price` decimal(5,2) NOT NULL,
  `pricing_excuse_percent` decimal(5,2) NOT NULL,
  `pricing_rate_percent` decimal(5,2) NOT NULL,
  `pricing_rate_type` enum('amount','extra','not') NOT NULL,
  `pricing_client_bonus` enum('yes','no') NOT NULL,
  `pricing_client_bonus_percent` decimal(5,2) NOT NULL,
  `pricing_client_bonus_amount` int(11) NOT NULL,
  `pricing_supply_bonus` enum('yes','no') NOT NULL,
  `pricing_supply_bonus_percent` decimal(5,2) NOT NULL,
  `pricing_supply_bonus_amount` int(11) NOT NULL,
  `pricing_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients_pricing`
--

INSERT INTO `clients_pricing` (`pricing_sn`, `pricing_product_rate`, `pricing_start_date`, `pricing_end_date`, `pricing_selling_price`, `pricing_supply_price`, `pricing_supply_percent`, `pricing_excuse_active`, `pricing_excuse_price`, `pricing_excuse_percent`, `pricing_rate_percent`, `pricing_rate_type`, `pricing_client_bonus`, `pricing_client_bonus_percent`, `pricing_client_bonus_amount`, `pricing_supply_bonus`, `pricing_supply_bonus_percent`, `pricing_supply_bonus_amount`, `pricing_status`) VALUES
(1, 2, '2020-10-19', '2020-10-23', '1.50', '1.43', '5.00', 'on', '9.00', '10.00', '20.00', 'extra', 'no', '0.00', 0, 'no', '0.00', 0, 1),
(2, 1, '2020-10-19', '2020-10-23', '10.00', '9.50', '5.00', 'off', '0.00', '0.00', '40.00', 'extra', 'no', '0.00', 0, 'no', '0.00', 0, 1),
(3, 3, '2020-10-19', '0000-00-00', '10.00', '9.50', '5.00', 'off', '0.00', '0.00', '30.00', 'amount', 'no', '0.00', 0, 'no', '0.00', 0, 1),
(4, 2, '2020-10-24', '0000-00-00', '1.50', '1.43', '5.00', 'off', '0.00', '0.00', '30.00', 'extra', 'no', '0.00', 0, 'no', '0.00', 0, 1),
(5, 1, '2020-10-24', '0000-00-00', '10.00', '9.50', '5.00', 'on', '9.50', '10.00', '40.00', 'extra', 'no', '0.00', 0, 'no', '0.00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `collect_returns`
--

CREATE TABLE `collect_returns` (
  `collect_returns_sn` int(11) NOT NULL,
  `collect_returns_date` date NOT NULL,
  `collect_returns_person` enum('supplier','client') NOT NULL,
  `collect_id` int(11) NOT NULL,
  `collect_returns_value` decimal(11,2) NOT NULL,
  `collect_returns_insert_in` enum('bank','safe') NOT NULL,
  `collect_returns_bank_id` int(2) NOT NULL,
  `collect_returns_account_type` enum('current','credit','saving') NOT NULL,
  `collect_returns_account_id` int(11) NOT NULL,
  `collect_returns_status` tinyint(1) NOT NULL DEFAULT '1',
  `collect_returns_type` enum('cheque','cash') NOT NULL,
  `collect_returns_cheque_date` date NOT NULL,
  `collect_returns_cheque_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collect_returns`
--

INSERT INTO `collect_returns` (`collect_returns_sn`, `collect_returns_date`, `collect_returns_person`, `collect_id`, `collect_returns_value`, `collect_returns_insert_in`, `collect_returns_bank_id`, `collect_returns_account_type`, `collect_returns_account_id`, `collect_returns_status`, `collect_returns_type`, `collect_returns_cheque_date`, `collect_returns_cheque_number`) VALUES
(1, '2020-10-21', 'supplier', 4, '14500.00', 'safe', 0, '', 0, 1, 'cash', '0000-00-00', ''),
(2, '2020-10-21', 'supplier', 5, '9500.00', 'safe', 0, '', 0, 1, 'cash', '0000-00-00', ''),
(3, '2020-10-22', 'supplier', 7, '5000.00', 'safe', 0, '', 0, 1, 'cash', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `deposits_sn` int(11) NOT NULL,
  `deposits_date` date NOT NULL,
  `deposits_type` enum('cash','cheque') NOT NULL,
  `deposits_value` decimal(11,2) NOT NULL,
  `deposits_cheque_date` date NOT NULL,
  `deposits_cheque_number` varchar(50) NOT NULL,
  `deposits_insert_in` enum('bank','safe') NOT NULL,
  `deposits_bank_id` int(2) NOT NULL,
  `deposits_account_type` enum('current','credit','saving') NOT NULL,
  `deposits_account_id` int(11) NOT NULL,
  `deposits_client_id` int(11) NOT NULL,
  `deposits_product_id` int(11) NOT NULL,
  `deposits_cut_precent` decimal(5,2) NOT NULL,
  `deposits_cut_value` decimal(12,2) NOT NULL,
  `deposits_days` int(3) NOT NULL,
  `deposit_date_pay` date NOT NULL,
  `deposits_approved` tinyint(1) NOT NULL DEFAULT '0',
  `deposits_approved_date` date NOT NULL,
  `deposits_collected` tinyint(1) NOT NULL DEFAULT '0',
  `deposits_collected_value` decimal(9,2) NOT NULL,
  `deposits_collected_date` date NOT NULL,
  `deposit_money_pull` decimal(9,2) NOT NULL,
  `deposit_benefits` decimal(9,2) NOT NULL,
  `deposits_pull_total` decimal(9,2) NOT NULL,
  `deposits_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposits_invoices`
--

CREATE TABLE `deposits_invoices` (
  `deposits_invoices_sn` int(11) NOT NULL,
  `deposits_id` int(11) NOT NULL,
  `invoices_id` int(11) NOT NULL,
  `value` decimal(9,2) NOT NULL,
  `paid` decimal(9,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenses_sn` int(11) NOT NULL,
  `expenses_date` date NOT NULL,
  `expenses_type` enum('cash','cheque') NOT NULL,
  `expenses_amount` decimal(11,2) NOT NULL,
  `expenses_cheque_date` date NOT NULL,
  `expenses_cheque_sn` char(30) NOT NULL,
  `expenses_in` enum('safe','bank') NOT NULL,
  `expenses_bank_id` int(3) NOT NULL,
  `expenses_bank_account_type` enum('credit','saving','current') NOT NULL,
  `expenses_bank_account_id` int(3) NOT NULL,
  `expenses_title` varchar(150) NOT NULL,
  `expenses_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `money_transfers`
--

CREATE TABLE `money_transfers` (
  `transfers_sn` int(11) NOT NULL,
  `transfers_date` date NOT NULL,
  `transfers_from_in` enum('bank','safe') NOT NULL,
  `transfers_from` int(11) NOT NULL,
  `transfers_account_type_from` enum('current','credit','saving') NOT NULL,
  `transfers_account_id_from` int(11) NOT NULL,
  `transfers_client_id_from` int(11) NOT NULL,
  `transfers_product_id_from` int(11) NOT NULL,
  `transfers_value` decimal(9,2) NOT NULL,
  `transfers_type` enum('cash','cheque') NOT NULL,
  `transfers_cheque_date` date NOT NULL,
  `transfers_cheque_number` varchar(50) NOT NULL,
  `transfers_to_in` enum('bank','safe') NOT NULL,
  `transfers_to` int(11) NOT NULL,
  `transfers_account_type_to` enum('current','credit','saving') NOT NULL,
  `transfers_account_id_to` int(11) NOT NULL,
  `transfers_client_id_to` int(11) NOT NULL,
  `transfers_product_id_to` int(11) NOT NULL,
  `transfers_cut_precent` decimal(5,2) NOT NULL,
  `transfers_cut_value` decimal(9,2) NOT NULL,
  `transfers_days` int(3) NOT NULL,
  `transfers_date_pay` date NOT NULL,
  `invoices_id` int(11) NOT NULL,
  `transfers_status` tinyint(1) NOT NULL DEFAULT '1',
  `transfers_bank_approved` tinyint(1) NOT NULL,
  `transfers_bank_approved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE `operations` (
  `operations_sn` int(11) NOT NULL,
  `operations_receipt` int(11) NOT NULL,
  `operations_code` varchar(8) NOT NULL,
  `operations_card_number` varchar(15) NOT NULL,
  `operations_date` date NOT NULL,
  `operations_supplier` int(11) NOT NULL,
  `operations_customer` int(11) NOT NULL,
  `operations_product` int(11) NOT NULL,
  `operations_supplier_price` decimal(11,2) NOT NULL,
  `operations_customer_price` decimal(11,2) NOT NULL,
  `operations_quantity` int(6) NOT NULL,
  `operations_general_discount` decimal(5,2) NOT NULL,
  `operations_net_quantity` int(6) NOT NULL,
  `operations_card_front_photo` char(15) NOT NULL,
  `operations_card_back_photo` char(15) NOT NULL,
  `operations_customer_paid` decimal(9,2) NOT NULL,
  `operations_customer_remain` decimal(9,2) NOT NULL,
  `operations_supplier_paid` decimal(9,2) NOT NULL,
  `operations_supplier_remain` decimal(9,2) NOT NULL,
  `operations_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operations`
--

INSERT INTO `operations` (`operations_sn`, `operations_receipt`, `operations_code`, `operations_card_number`, `operations_date`, `operations_supplier`, `operations_customer`, `operations_product`, `operations_supplier_price`, `operations_customer_price`, `operations_quantity`, `operations_general_discount`, `operations_net_quantity`, `operations_card_front_photo`, `operations_card_back_photo`, `operations_customer_paid`, `operations_customer_remain`, `operations_supplier_paid`, `operations_supplier_remain`, `operations_status`) VALUES
(1, 1, '0114', '1', '2020-10-21', 1, 1, 2, '19000.00', '20000.00', 2000, '0.00', 2000, '', '', '0.00', '20000.00', '0.00', '19000.00', 0),
(2, 2, 'fb05', '2', '2020-10-21', 1, 2, 1, '9500.00', '10000.00', 1000, '0.00', 1000, '', '', '0.00', '10000.00', '0.00', '9500.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `operations_rates`
--

CREATE TABLE `operations_rates` (
  `rates_sn` int(11) NOT NULL,
  `rates_operation_id` int(11) NOT NULL,
  `rates_product_rate_id` int(11) NOT NULL,
  `rates_supplier_discount_percentage` decimal(4,2) NOT NULL,
  `rates_supplier_discount_value` int(7) NOT NULL,
  `rates_product_rate_percentage` decimal(4,2) NOT NULL,
  `rates_product_rate_discount_percentage` decimal(4,2) NOT NULL,
  `rates_product_rate_excuse_percentage` decimal(4,2) NOT NULL,
  `rates_product_rate_excuse_price` decimal(5,2) NOT NULL,
  `rates_product_rate_supply_price` decimal(5,2) NOT NULL,
  `rates_product_rate_quantity` int(7) NOT NULL,
  `rates_product_rate_excuse_quantity` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operations_rates`
--

INSERT INTO `operations_rates` (`rates_sn`, `rates_operation_id`, `rates_product_rate_id`, `rates_supplier_discount_percentage`, `rates_supplier_discount_value`, `rates_product_rate_percentage`, `rates_product_rate_discount_percentage`, `rates_product_rate_excuse_percentage`, `rates_product_rate_excuse_price`, `rates_product_rate_supply_price`, `rates_product_rate_quantity`, `rates_product_rate_excuse_quantity`) VALUES
(1, 1, 2, '5.00', 1, '20.00', '0.00', '0.00', '9.00', '1.42', 0, 0),
(2, 1, 1, '5.00', 10, '40.00', '0.00', '0.00', '0.00', '9.50', 2000, 0),
(3, 2, 3, '5.00', 10, '30.00', '0.00', '0.00', '0.00', '9.50', 1000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `reminders_sn` int(11) NOT NULL,
  `reminders_type` varchar(25) NOT NULL,
  `reminders_type_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `reminders_date` date NOT NULL,
  `reminders_type_reminder` enum('day','month','year') NOT NULL,
  `reminders_number_reminder` int(3) NOT NULL,
  `reminders_remember_date` date NOT NULL,
  `reminders_notification_date` date NOT NULL,
  `reminders_read` tinyint(1) NOT NULL DEFAULT '0',
  `reminders_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`reminders_sn`, `reminders_type`, `reminders_type_id`, `client_id`, `product_id`, `title`, `reminders_date`, `reminders_type_reminder`, `reminders_number_reminder`, `reminders_remember_date`, `reminders_notification_date`, `reminders_read`, `reminders_status`) VALUES
(1, 'clients_pay', 0, 1, 2, '5000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(2, 'clients_pay', 0, 1, 2, '5000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(3, 'suppliers_collectible', 2, 0, 0, '', '2020-10-21', 'day', 7, '2020-10-14', '2020-10-14', 1, 2),
(4, 'clients_pay', 0, 1, 2, '5000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(5, 'clients_pay', 0, 1, 2, '5000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(6, 'clients_pay', 0, 1, 2, '10000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(7, 'clients_pay', 0, 1, 2, '10000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(8, 'suppliers_collectible', 3, 0, 0, '', '2020-11-07', 'day', 7, '2020-10-31', '2020-10-31', 0, 1),
(9, 'suppliers_collectible', 4, 0, 0, '', '2020-10-24', 'day', 7, '2020-10-17', '2020-10-17', 1, 2),
(10, 'clients_pay', 0, 1, 2, '10000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(11, 'clients_pay', 0, 1, 2, '10000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1),
(12, 'clients_pay', 0, 2, 1, '5000', '2020-11-05', 'day', 7, '2020-10-29', '2020-10-29', 0, 1),
(13, 'clients_pay', 0, 2, 1, '5000', '2020-11-10', 'day', 7, '2020-11-03', '2020-11-03', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `setiings_banks_finance`
--

CREATE TABLE `setiings_banks_finance` (
  `banks_finance_sn` int(11) NOT NULL,
  `banks_finance_bank_id` int(2) NOT NULL,
  `banks_finance_account_type` enum('credit','current','saving') NOT NULL,
  `banks_finance_account_id` int(2) NOT NULL,
  `banks_finance_open_balance` decimal(11,2) NOT NULL,
  `banks_finance_credit` decimal(11,2) NOT NULL,
  `banks_benefits` decimal(11,2) NOT NULL,
  `banks_total_with_benefits` decimal(11,2) NOT NULL,
  `banks_finance_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setiings_banks_finance`
--

INSERT INTO `setiings_banks_finance` (`banks_finance_sn`, `banks_finance_bank_id`, `banks_finance_account_type`, `banks_finance_account_id`, `banks_finance_open_balance`, `banks_finance_credit`, `banks_benefits`, `banks_total_with_benefits`, `banks_finance_status`) VALUES
(1, 1, 'credit', 1, '5000000.00', '0.00', '0.00', '0.00', 1),
(2, 1, 'current', 1, '1000000.00', '0.00', '0.00', '0.00', 1),
(3, 2, 'credit', 2, '5000000.00', '0.00', '0.00', '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_banks`
--

CREATE TABLE `settings_banks` (
  `banks_sn` int(2) NOT NULL,
  `banks_name` varchar(50) NOT NULL,
  `banks_account_number` varchar(30) NOT NULL,
  `banks_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_banks`
--

INSERT INTO `settings_banks` (`banks_sn`, `banks_name`, `banks_account_number`, `banks_status`) VALUES
(1, 'بنك الكويت', '123', 1),
(2, 'بنك عودة', '456', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_banks_credit`
--

CREATE TABLE `settings_banks_credit` (
  `banks_credit_sn` int(11) NOT NULL,
  `banks_credit_bank_id` int(2) NOT NULL,
  `banks_credit_name` varchar(50) NOT NULL,
  `banks_credit_code` varchar(20) NOT NULL,
  `banks_credit_open_balance` decimal(11,2) NOT NULL,
  `banks_credit_repayment_period` int(3) NOT NULL,
  `banks_credit_repayment_type` enum('day','date') NOT NULL,
  `banks_credit_interest_rate` decimal(5,2) NOT NULL,
  `banks_credit_duration_of_interest` int(3) NOT NULL,
  `banks_credit_limit_value` decimal(11,2) NOT NULL,
  `banks_credit_cutting_ratio` decimal(5,2) NOT NULL,
  `banks_credit_client` int(11) NOT NULL,
  `banks_credit_product` int(3) NOT NULL,
  `banks_credit_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_banks_credit`
--

INSERT INTO `settings_banks_credit` (`banks_credit_sn`, `banks_credit_bank_id`, `banks_credit_name`, `banks_credit_code`, `banks_credit_open_balance`, `banks_credit_repayment_period`, `banks_credit_repayment_type`, `banks_credit_interest_rate`, `banks_credit_duration_of_interest`, `banks_credit_limit_value`, `banks_credit_cutting_ratio`, `banks_credit_client`, `banks_credit_product`, `banks_credit_status`) VALUES
(1, 1, 'فواتير فارم', '111', '0.00', 90, 'day', '14.00', 365, '5000000.00', '70.00', 0, 0, 1),
(2, 2, 'قطع شيكات', '12457485', '0.00', 90, 'date', '14.00', 365, '5000000.00', '70.00', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_banks_current`
--

CREATE TABLE `settings_banks_current` (
  `banks_current_sn` int(11) NOT NULL,
  `banks_current_bank_id` int(2) NOT NULL,
  `banks_current_account_number` varchar(30) NOT NULL,
  `banks_current_opening_balance` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_banks_current`
--

INSERT INTO `settings_banks_current` (`banks_current_sn`, `banks_current_bank_id`, `banks_current_account_number`, `banks_current_opening_balance`) VALUES
(1, 1, '14547', '1000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `settings_banks_saving`
--

CREATE TABLE `settings_banks_saving` (
  `banks_saving_sn` int(11) NOT NULL,
  `banks_saving_bank_id` int(2) NOT NULL,
  `banks_saving_account_number` varchar(30) NOT NULL,
  `banks_saving_open_balance` decimal(11,2) NOT NULL,
  `banks_saving_interest_rate` decimal(5,2) NOT NULL,
  `banks_saving_duration_of_interest` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_clients`
--

CREATE TABLE `settings_clients` (
  `clients_sn` int(11) NOT NULL,
  `clients_name` varchar(50) NOT NULL,
  `clients_manager_name` varchar(50) NOT NULL,
  `clients_phone_one` char(11) NOT NULL,
  `clients_phone_two` char(11) NOT NULL,
  `clients_manager_phone` char(11) NOT NULL,
  `clients_manager_email` varchar(50) NOT NULL,
  `clients_tex_card` char(15) NOT NULL,
  `clients_commercial_register` char(15) NOT NULL,
  `clients_email` varchar(50) NOT NULL,
  `clients_address` varchar(100) NOT NULL,
  `clients_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_clients`
--

INSERT INTO `settings_clients` (`clients_sn`, `clients_name`, `clients_manager_name`, `clients_phone_one`, `clients_phone_two`, `clients_manager_phone`, `clients_manager_email`, `clients_tex_card`, `clients_commercial_register`, `clients_email`, `clients_address`, `clients_status`) VALUES
(1, 'بسمة', 'مصطفى ياسر', '', '', '01245788987', '', '', '', '', 'العاشر من رمضان', 1),
(2, 'مونتانة', 'مقبل امين', '', '', '01245788977', '', '', '', '', '20 اكتوبر', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_clients_payments`
--

CREATE TABLE `settings_clients_payments` (
  `clients_payments_sn` int(11) NOT NULL,
  `clients_payments_client_id` int(11) NOT NULL,
  `clients_payments_days` int(3) NOT NULL,
  `clients_payments_percent` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_clients_payments`
--

INSERT INTO `settings_clients_payments` (`clients_payments_sn`, `clients_payments_client_id`, `clients_payments_days`, `clients_payments_percent`) VALUES
(1, 1, 20, '50.00'),
(2, 1, 20, '50.00'),
(3, 1, 0, '0.00'),
(4, 1, 0, '0.00'),
(5, 1, 0, '0.00'),
(6, 2, 15, '50.00'),
(7, 2, 20, '50.00'),
(8, 2, 0, '0.00'),
(9, 2, 0, '0.00'),
(10, 2, 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `settings_clients_products`
--

CREATE TABLE `settings_clients_products` (
  `clients_products_sn` int(11) NOT NULL,
  `clients_products_client_id` int(11) NOT NULL,
  `clients_products_product_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_clients_products`
--

INSERT INTO `settings_clients_products` (`clients_products_sn`, `clients_products_client_id`, `clients_products_product_id`) VALUES
(1, 1, 2),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_clients_products_rate`
--

CREATE TABLE `settings_clients_products_rate` (
  `clients_products_rate_sn` int(11) NOT NULL,
  `clients_products_rate_product_id` int(11) NOT NULL,
  `clients_products_rate_name` varchar(50) NOT NULL,
  `clients_products_rate_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_clients_products_rate`
--

INSERT INTO `settings_clients_products_rate` (`clients_products_rate_sn`, `clients_products_rate_product_id`, `clients_products_rate_name`, `clients_products_rate_status`) VALUES
(1, 1, 'زيرو', 1),
(2, 1, 'ويكا', 1),
(3, 2, 'موحدة', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_companyinfo`
--

CREATE TABLE `settings_companyinfo` (
  `companyinfo_sn` int(1) NOT NULL,
  `companyinfo_name` varchar(50) NOT NULL,
  `companyinfo_address` text NOT NULL,
  `companyinfo_phone` char(11) NOT NULL,
  `companyinfo_opening_balance_safe` decimal(11,2) NOT NULL,
  `companyinfo_opening_balance_cheques` decimal(11,2) NOT NULL,
  `companyinfo_logo` char(15) NOT NULL,
  `companyinfo_document` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_companyinfo`
--

INSERT INTO `settings_companyinfo` (`companyinfo_sn`, `companyinfo_name`, `companyinfo_address`, `companyinfo_phone`, `companyinfo_opening_balance_safe`, `companyinfo_opening_balance_cheques`, `companyinfo_logo`, `companyinfo_document`) VALUES
(1, 'شركة المجد للتوريدات العامة', 'شارع التسعين', '01114448822', '89500.00', '28000.00', '04e5ce9eeb.png', '81b81e683d.png');

-- --------------------------------------------------------

--
-- Table structure for table `settings_departments`
--

CREATE TABLE `settings_departments` (
  `departments_sn` int(2) NOT NULL,
  `departments_name` varchar(50) NOT NULL,
  `departments_description` varchar(255) NOT NULL,
  `departments_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_departments`
--

INSERT INTO `settings_departments` (`departments_sn`, `departments_name`, `departments_description`, `departments_status`) VALUES
(1, 'المالية', 'الادارة الخاصة بالحسابات بب', 1),
(7, 'الشئون القانونية', 'الادارة الخاصة الشئون القانونية', 1),
(8, 'الشئون الفنية', 'الادارة الخاصة الشئون الفنية', 0),
(9, 'الشئون المعنوية', 'الادارة الخاصة بالحسابات', 1),
(10, 'l', 'l', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_jobs`
--

CREATE TABLE `settings_jobs` (
  `jobs_sn` int(2) NOT NULL,
  `jobs_name` varchar(50) NOT NULL,
  `jobs_department` int(2) NOT NULL,
  `jobs_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_jobs`
--

INSERT INTO `settings_jobs` (`jobs_sn`, `jobs_name`, `jobs_department`, `jobs_status`) VALUES
(1, 'وظيفة', 1, 1),
(2, 'وظيفة 3', 1, 1),
(3, 'وظيفة 3', 1, 0),
(4, 'HR', 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_products`
--

CREATE TABLE `settings_products` (
  `products_sn` int(3) NOT NULL,
  `products_name` varchar(50) NOT NULL,
  `products_description` varchar(255) NOT NULL,
  `products_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_products`
--

INSERT INTO `settings_products` (`products_sn`, `products_name`, `products_description`, `products_status`) VALUES
(1, 'فاصولية', 'فاصولية', 1),
(2, 'بامية', 'بامية', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_stocks`
--

CREATE TABLE `settings_stocks` (
  `stocks_sn` int(2) NOT NULL,
  `stocks_name` varchar(50) NOT NULL,
  `stocks_manager_name` varchar(50) NOT NULL,
  `stocks_phone_one` char(11) NOT NULL,
  `stocks_phone_two` char(11) NOT NULL,
  `stocks_manager_phone` char(11) NOT NULL,
  `stocks_email` varchar(50) NOT NULL,
  `stocks_address` varchar(100) NOT NULL,
  `stocks_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_stocks_products`
--

CREATE TABLE `settings_stocks_products` (
  `stocks_products_sn` int(11) NOT NULL,
  `stocks_products_stock_id` int(2) NOT NULL,
  `stocks_products_product_id` int(3) NOT NULL,
  `stocks_products_rate_one` varchar(50) NOT NULL,
  `stocks_products_rate_two` varchar(50) NOT NULL,
  `stocks_products_rate_three` varchar(50) NOT NULL,
  `stocks_products_rate_four` varchar(50) NOT NULL,
  `stocks_products_rate_five` varchar(50) NOT NULL,
  `stocks_products_rate_sex` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_suppliers`
--

CREATE TABLE `settings_suppliers` (
  `suppliers_sn` int(11) NOT NULL,
  `suppliers_name` varchar(50) NOT NULL,
  `suppliers_phone_one` char(11) NOT NULL,
  `suppliers_phone_two` char(11) NOT NULL,
  `suppliers_photo` char(15) NOT NULL,
  `suppliers_doc` char(15) NOT NULL,
  `suppliers_address` varchar(100) NOT NULL,
  `suppliers_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_suppliers`
--

INSERT INTO `settings_suppliers` (`suppliers_sn`, `suppliers_name`, `suppliers_phone_one`, `suppliers_phone_two`, `suppliers_photo`, `suppliers_doc`, `suppliers_address`, `suppliers_status`) VALUES
(1, 'على عبد الستار', '01245788987', '', '', '', 'العياط', 1),
(2, 'سعد عبدة', '01245771346', '', '', '', 'القليوبية', 1),
(3, 'عصام امين', '01245771245', '', '', '', 'العاشر من رمضان', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_suppliers_products`
--

CREATE TABLE `settings_suppliers_products` (
  `suppliers_products_sn` int(11) NOT NULL,
  `suppliers_products_supplier_id` int(11) NOT NULL,
  `suppliers_products_product_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_suppliers_products`
--

INSERT INTO `settings_suppliers_products` (`suppliers_products_sn`, `suppliers_products_supplier_id`, `suppliers_products_product_id`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 2),
(4, 3, 1),
(5, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_users`
--

CREATE TABLE `settings_users` (
  `users_sn` int(11) NOT NULL,
  `users_name` varchar(100) NOT NULL,
  `users_birthday` date NOT NULL,
  `users_department_id` int(2) NOT NULL,
  `users_job_id` int(2) NOT NULL,
  `users_qualification` varchar(25) NOT NULL,
  `users_graduation_year` year(4) NOT NULL,
  `users_phone` char(11) NOT NULL,
  `users_email` varchar(50) NOT NULL,
  `users_photo` char(15) NOT NULL,
  `users_address` varchar(150) NOT NULL,
  `users_password` varchar(255) NOT NULL,
  `users_group` int(2) NOT NULL,
  `users_salary` decimal(7,2) NOT NULL,
  `users_last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 is delete ,1 is active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_users`
--

INSERT INTO `settings_users` (`users_sn`, `users_name`, `users_birthday`, `users_department_id`, `users_job_id`, `users_qualification`, `users_graduation_year`, `users_phone`, `users_email`, `users_photo`, `users_address`, `users_password`, `users_group`, `users_salary`, `users_last_login`, `users_status`) VALUES
(1, 'احمد ابو المجد', '1976-10-10', 10, 1, 'computer  science', 1999, '01114448822', 'Ahmed85@gmail.com', '0f8367ee53.png', 'التجمع الخامس', '$2y$10$a5bzk.RxnAshdZoqKyeu/OLdBGeSH2FSZzWcCKXzQ3HQKw9aqlssi', -1, '50000.00', '2020-10-20 16:22:04', 1),
(2, 'أحمد فوزى2', '2020-07-11', 1, 1, 'علوم2', 2012, '01020451211', 'Ahmed84@gmail.com', '0e776089ea.png', 'شارع محمد على 1', '$2y$10$qgVc472P2A1dmEdWO.SuQubulRKaXJVCSwzN9dftvja1Q.G4NO8FS', 1, '1100.00', '2020-07-16 02:01:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings_user_group`
--

CREATE TABLE `settings_user_group` (
  `group_sn` int(2) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `group_description` text NOT NULL,
  `setting_company` tinyint(1) NOT NULL DEFAULT '1',
  `settings_department` tinyint(1) NOT NULL DEFAULT '1',
  `setting_jobs` tinyint(1) NOT NULL DEFAULT '1',
  `setting_stocks` tinyint(1) NOT NULL DEFAULT '1',
  `settings_products` tinyint(1) NOT NULL DEFAULT '1',
  `settings_users` tinyint(1) NOT NULL DEFAULT '1',
  `settings_clients` tinyint(1) NOT NULL DEFAULT '1',
  `settings_suppliers` tinyint(1) NOT NULL DEFAULT '1',
  `settings_banks` tinyint(1) NOT NULL DEFAULT '1',
  `settings_user_group` tinyint(1) NOT NULL DEFAULT '1',
  `clients_pricing` tinyint(1) NOT NULL DEFAULT '1',
  `clients_finance` tinyint(1) NOT NULL DEFAULT '1',
  `clients_old_pricing` tinyint(1) NOT NULL DEFAULT '1',
  `clients_payments` tinyint(1) NOT NULL DEFAULT '1',
  `operations` tinyint(1) NOT NULL DEFAULT '1',
  `expense` tinyint(1) NOT NULL DEFAULT '1',
  `deposit_check` tinyint(1) NOT NULL DEFAULT '1',
  `bank_transfer` tinyint(1) DEFAULT '1',
  `client_payment` tinyint(1) NOT NULL DEFAULT '1',
  `supplier_payment` tinyint(1) NOT NULL DEFAULT '1',
  `reminders` tinyint(1) NOT NULL DEFAULT '1',
  `delete_deposits` tinyint(1) NOT NULL DEFAULT '1',
  `group_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_user_group`
--

INSERT INTO `settings_user_group` (`group_sn`, `group_name`, `group_description`, `setting_company`, `settings_department`, `setting_jobs`, `setting_stocks`, `settings_products`, `settings_users`, `settings_clients`, `settings_suppliers`, `settings_banks`, `settings_user_group`, `clients_pricing`, `clients_finance`, `clients_old_pricing`, `clients_payments`, `operations`, `expense`, `deposit_check`, `bank_transfer`, `client_payment`, `supplier_payment`, `reminders`, `delete_deposits`, `group_status`) VALUES
(1, 'الادمن', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_collectible`
--

CREATE TABLE `suppliers_collectible` (
  `collectible_sn` int(11) NOT NULL,
  `collectible_supplier_id` int(11) NOT NULL,
  `collectible_date` date NOT NULL,
  `collectible_type` enum('cash','cheque') NOT NULL,
  `collectible_value` decimal(11,2) NOT NULL,
  `collectible_cheque_date` date NOT NULL,
  `collectible_cheque_number` varchar(50) NOT NULL,
  `collectible_insert_in` enum('safe','bank') NOT NULL,
  `collectible_bank_id` int(2) NOT NULL,
  `collectible_account_type` enum('current','credit','saving') NOT NULL,
  `collectible_account_id` int(11) NOT NULL,
  `collectible_payment_case` enum('paid','later','return') NOT NULL,
  `collectible_recipient` varchar(50) NOT NULL,
  `collectible_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers_collectible`
--

INSERT INTO `suppliers_collectible` (`collectible_sn`, `collectible_supplier_id`, `collectible_date`, `collectible_type`, `collectible_value`, `collectible_cheque_date`, `collectible_cheque_number`, `collectible_insert_in`, `collectible_bank_id`, `collectible_account_type`, `collectible_account_id`, `collectible_payment_case`, `collectible_recipient`, `collectible_status`) VALUES
(3, 1, '2020-10-21', 'cash', '5000.00', '0000-00-00', '', 'safe', 0, '', 0, 'later', 'أحمد فوزى', 1),
(4, 1, '2020-10-21', 'cash', '14500.00', '0000-00-00', '', 'safe', 0, 'current', 0, 'return', '1', 0),
(7, 1, '2020-10-21', 'cash', '5000.00', '0000-00-00', '', 'safe', 0, 'current', 0, 'return', '2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_finance`
--

CREATE TABLE `suppliers_finance` (
  `suppliers_finance_sn` int(11) NOT NULL,
  `suppliers_finance_supplier_id` int(11) NOT NULL,
  `suppliers_finance_credit` decimal(11,2) NOT NULL,
  `suppliers_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers_finance`
--

INSERT INTO `suppliers_finance` (`suppliers_finance_sn`, `suppliers_finance_supplier_id`, `suppliers_finance_credit`, `suppliers_status`) VALUES
(1, 1, '-5000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_collectible_operations`
--

CREATE TABLE `supplier_collectible_operations` (
  `id` int(11) NOT NULL,
  `collectible_id` int(11) NOT NULL,
  `operations_id` int(11) NOT NULL,
  `value` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier_collectible_operations`
--

INSERT INTO `supplier_collectible_operations` (`id`, `collectible_id`, `operations_id`, `value`) VALUES
(3, 4, 1, '14500.00'),
(6, 7, 2, '5000.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients_collectible`
--
ALTER TABLE `clients_collectible`
  ADD PRIMARY KEY (`collectible_sn`);

--
-- Indexes for table `clients_collectible_operations`
--
ALTER TABLE `clients_collectible_operations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients_finance`
--
ALTER TABLE `clients_finance`
  ADD PRIMARY KEY (`clients_finance_sn`);

--
-- Indexes for table `clients_pricing`
--
ALTER TABLE `clients_pricing`
  ADD PRIMARY KEY (`pricing_sn`);

--
-- Indexes for table `collect_returns`
--
ALTER TABLE `collect_returns`
  ADD PRIMARY KEY (`collect_returns_sn`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`deposits_sn`);

--
-- Indexes for table `deposits_invoices`
--
ALTER TABLE `deposits_invoices`
  ADD PRIMARY KEY (`deposits_invoices_sn`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenses_sn`);

--
-- Indexes for table `money_transfers`
--
ALTER TABLE `money_transfers`
  ADD PRIMARY KEY (`transfers_sn`);

--
-- Indexes for table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`operations_sn`),
  ADD UNIQUE KEY `operations_receipt` (`operations_receipt`);

--
-- Indexes for table `operations_rates`
--
ALTER TABLE `operations_rates`
  ADD PRIMARY KEY (`rates_sn`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`reminders_sn`);

--
-- Indexes for table `setiings_banks_finance`
--
ALTER TABLE `setiings_banks_finance`
  ADD PRIMARY KEY (`banks_finance_sn`);

--
-- Indexes for table `settings_banks`
--
ALTER TABLE `settings_banks`
  ADD PRIMARY KEY (`banks_sn`);

--
-- Indexes for table `settings_banks_credit`
--
ALTER TABLE `settings_banks_credit`
  ADD PRIMARY KEY (`banks_credit_sn`);

--
-- Indexes for table `settings_banks_current`
--
ALTER TABLE `settings_banks_current`
  ADD PRIMARY KEY (`banks_current_sn`);

--
-- Indexes for table `settings_banks_saving`
--
ALTER TABLE `settings_banks_saving`
  ADD PRIMARY KEY (`banks_saving_sn`);

--
-- Indexes for table `settings_clients`
--
ALTER TABLE `settings_clients`
  ADD PRIMARY KEY (`clients_sn`);

--
-- Indexes for table `settings_clients_payments`
--
ALTER TABLE `settings_clients_payments`
  ADD PRIMARY KEY (`clients_payments_sn`);

--
-- Indexes for table `settings_clients_products`
--
ALTER TABLE `settings_clients_products`
  ADD PRIMARY KEY (`clients_products_sn`);

--
-- Indexes for table `settings_clients_products_rate`
--
ALTER TABLE `settings_clients_products_rate`
  ADD PRIMARY KEY (`clients_products_rate_sn`);

--
-- Indexes for table `settings_companyinfo`
--
ALTER TABLE `settings_companyinfo`
  ADD PRIMARY KEY (`companyinfo_sn`);

--
-- Indexes for table `settings_departments`
--
ALTER TABLE `settings_departments`
  ADD PRIMARY KEY (`departments_sn`);

--
-- Indexes for table `settings_jobs`
--
ALTER TABLE `settings_jobs`
  ADD PRIMARY KEY (`jobs_sn`);

--
-- Indexes for table `settings_products`
--
ALTER TABLE `settings_products`
  ADD PRIMARY KEY (`products_sn`);

--
-- Indexes for table `settings_stocks`
--
ALTER TABLE `settings_stocks`
  ADD PRIMARY KEY (`stocks_sn`),
  ADD UNIQUE KEY `stocks_phone_one` (`stocks_phone_one`),
  ADD UNIQUE KEY `stocks_phone_two` (`stocks_phone_two`),
  ADD UNIQUE KEY `stocks_manager_phone` (`stocks_manager_phone`),
  ADD UNIQUE KEY `stocks_email` (`stocks_email`);

--
-- Indexes for table `settings_stocks_products`
--
ALTER TABLE `settings_stocks_products`
  ADD PRIMARY KEY (`stocks_products_sn`);

--
-- Indexes for table `settings_suppliers`
--
ALTER TABLE `settings_suppliers`
  ADD PRIMARY KEY (`suppliers_sn`);

--
-- Indexes for table `settings_suppliers_products`
--
ALTER TABLE `settings_suppliers_products`
  ADD PRIMARY KEY (`suppliers_products_sn`);

--
-- Indexes for table `settings_users`
--
ALTER TABLE `settings_users`
  ADD PRIMARY KEY (`users_sn`),
  ADD UNIQUE KEY `users_name` (`users_name`),
  ADD UNIQUE KEY `users_phone` (`users_phone`);

--
-- Indexes for table `settings_user_group`
--
ALTER TABLE `settings_user_group`
  ADD PRIMARY KEY (`group_sn`);

--
-- Indexes for table `suppliers_collectible`
--
ALTER TABLE `suppliers_collectible`
  ADD PRIMARY KEY (`collectible_sn`);

--
-- Indexes for table `suppliers_finance`
--
ALTER TABLE `suppliers_finance`
  ADD PRIMARY KEY (`suppliers_finance_sn`);

--
-- Indexes for table `supplier_collectible_operations`
--
ALTER TABLE `supplier_collectible_operations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients_collectible`
--
ALTER TABLE `clients_collectible`
  MODIFY `collectible_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_collectible_operations`
--
ALTER TABLE `clients_collectible_operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_finance`
--
ALTER TABLE `clients_finance`
  MODIFY `clients_finance_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clients_pricing`
--
ALTER TABLE `clients_pricing`
  MODIFY `pricing_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `collect_returns`
--
ALTER TABLE `collect_returns`
  MODIFY `collect_returns_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `deposits_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits_invoices`
--
ALTER TABLE `deposits_invoices`
  MODIFY `deposits_invoices_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `money_transfers`
--
ALTER TABLE `money_transfers`
  MODIFY `transfers_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operations`
--
ALTER TABLE `operations`
  MODIFY `operations_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `operations_rates`
--
ALTER TABLE `operations_rates`
  MODIFY `rates_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `reminders_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `setiings_banks_finance`
--
ALTER TABLE `setiings_banks_finance`
  MODIFY `banks_finance_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings_banks`
--
ALTER TABLE `settings_banks`
  MODIFY `banks_sn` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_banks_credit`
--
ALTER TABLE `settings_banks_credit`
  MODIFY `banks_credit_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_banks_current`
--
ALTER TABLE `settings_banks_current`
  MODIFY `banks_current_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings_banks_saving`
--
ALTER TABLE `settings_banks_saving`
  MODIFY `banks_saving_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_clients`
--
ALTER TABLE `settings_clients`
  MODIFY `clients_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_clients_payments`
--
ALTER TABLE `settings_clients_payments`
  MODIFY `clients_payments_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings_clients_products`
--
ALTER TABLE `settings_clients_products`
  MODIFY `clients_products_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_clients_products_rate`
--
ALTER TABLE `settings_clients_products_rate`
  MODIFY `clients_products_rate_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings_companyinfo`
--
ALTER TABLE `settings_companyinfo`
  MODIFY `companyinfo_sn` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings_departments`
--
ALTER TABLE `settings_departments`
  MODIFY `departments_sn` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings_jobs`
--
ALTER TABLE `settings_jobs`
  MODIFY `jobs_sn` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings_products`
--
ALTER TABLE `settings_products`
  MODIFY `products_sn` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_stocks`
--
ALTER TABLE `settings_stocks`
  MODIFY `stocks_sn` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_stocks_products`
--
ALTER TABLE `settings_stocks_products`
  MODIFY `stocks_products_sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_suppliers`
--
ALTER TABLE `settings_suppliers`
  MODIFY `suppliers_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings_suppliers_products`
--
ALTER TABLE `settings_suppliers_products`
  MODIFY `suppliers_products_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings_user_group`
--
ALTER TABLE `settings_user_group`
  MODIFY `group_sn` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers_collectible`
--
ALTER TABLE `suppliers_collectible`
  MODIFY `collectible_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suppliers_finance`
--
ALTER TABLE `suppliers_finance`
  MODIFY `suppliers_finance_sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_collectible_operations`
--
ALTER TABLE `supplier_collectible_operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
