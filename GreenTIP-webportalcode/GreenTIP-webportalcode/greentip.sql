-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 29, 2019 at 02:22 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 5.6.33-3+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greentip`
--

-- --------------------------------------------------------

--
-- Table structure for table `b_auto_login`
--

CREATE TABLE `b_auto_login` (
  `user` int(11) NOT NULL,
  `series` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `type` enum('user','admin') DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_auto_login`
--

INSERT INTO `b_auto_login` (`user`, `series`, `key`, `type`, `created_on`) VALUES
(1, '406017975bb05409f7d8bc8a30b990a17c39a0e5758158938266172b14aab6a0', '5e9f90aa42f473a014df317faca004fb545fe2cdae25afe8e903d680ec11160d', 'user', '2019-10-16 11:06:09'),
(1, '4c3e87109f551cd1ae251c6c15e34de7c20e944420d157c93ab796c78ce7cf32', '1f4c758eb11ea2cdfc5c9f08115729b4cbeb02d69c827f4920b9e31ffeb1987e', 'user', '2019-10-21 16:35:48'),
(1, '540a45ab1718d511d8767be1cf630c95cd56c7eab1e223b10f8fa3c55083cde9', '4e049d9564846fdbea6e821771aa88ecbedbccba2908ca71fa0d836bc0da2373', 'user', '2019-10-31 11:26:42'),
(1, '5a2b6d05f0a2bbddfd1f482f6bf66ee52dc406f86d4791be082ef8b46048d440', '22094bb4ff7881d04d0bb829903b48bb25ba71690308c6a469662caf2436ffcf', 'user', '2019-10-16 16:35:25'),
(1, '71b4777a0659cf49750e338fe4cae36294d7853f7cceaf96a3d329753136b548', '0684faf76fcc6d28c0c9d6d2467cf877b23f3ef62ad45a2abe34a4593f823632', 'user', '2019-11-26 17:58:18'),
(1, '81914ed7cf38a6cdf2240fae52a0bb895113ad791529aaa583e784adbfdf925e', '670808fe3a6e1b2ee4dc5dfe6e4b39efbe6d9960f725ad30a7b1699382a6408f', 'user', '2019-10-16 13:07:39'),
(1, '81fb51a9519fa6b427135da8f7d29254d121941777df3ca5ec9032a9931f7cd1', '2609e4554223918d8462acc7096543aa8529166542478c8debea3eb53db71c27', 'user', '2019-10-16 17:11:31'),
(1, '8c326ad241ba651bb17117d09c5a6665c397758d7475449196a720b89d201e35', 'cde50d7862a09f81b73bafbd6349af9a6ef110f9853369b96f0ab2fd52676e4f', 'user', '2019-10-18 16:05:47'),
(1, '8e9d053a0cb9899ba2f22089b2104691e462917775f6a2ebc41d716530795d9d', 'df23446c75aa9fb57508802630f8f0c27a41ce80ed1bbc5579ce8c245e754c3c', 'user', '2019-10-16 12:11:16'),
(1, '99f47bcb8acd26bd48b9cd78496f6d5453da3369523b9124a592f3c1f67b553b', 'c3b6990f7e2f8dcdf6700225f838211b1fdec03660f81ea138bd51005741bd96', 'user', '2019-10-16 13:24:57'),
(1, 'a04b60a41f1dd4335d421745f563c548fcb903f4bf9defca887024515d950b81', 'a9d6b4075ed299fb9ed0366927b57c1c3337f1460d022d80d5021510a08b0e0b', 'user', '2019-10-25 16:30:20'),
(1, 'b3b1ff166b426d1a86a122ab4fc2abfe17cf5537413ad5bb1042f4e6d119ef73', '395bb38a9258edddaf9b65b49f3a42cd08fcb41336d32497929ffa5d0a3c356e', 'user', '2019-10-18 16:13:31'),
(1, 'c01eb3f3a281abd7d97320e525271088dd8feb152c0e7f1c3777575e772cafcb', 'eb5a6778f505420c02fa37cb40145339d91d36f81947bc148be1e55a42d01fae', 'user', '2019-10-16 12:03:27'),
(1, 'c0cc524cffa9af7ba629b03e9e1aaa772ee1d70edc72fb4f0508e17c40e7dd1b', '5f5682b1f47ab3ac92954337d2688911199696e73e18bf037b24c2b4f991841e', 'user', '2019-11-18 13:01:43'),
(1, 'dfc6c94babd236226fe9e0e0bad703e59171bee606ef5b9c4b64cf6ba9133ac5', '9483fc7a601eb391d80b2c10192152adfcdb876f2264a2319e0ce63a6645c679', 'user', '2019-10-16 12:20:31'),
(1, 'f85d72ce736487d4d1254651a408d7176add36ec21a0d65b9d72c6159bb2fd50', 'e3124c3c1edbf9fba43197df02a3127621a60eb57778f7284807cbfb1fb1580d', 'user', '2019-11-15 16:49:20'),
(1, 'fab15fc63d4e2c8e6feb36678e77745aa79dcede55ed53c07eacb5b45c77cfba', '3c50d1c59881be92f6e8dd0f0356425c617634a6ae796c806ff9ee0720ad6688', 'user', '2019-11-29 10:52:55'),
(19, '09af1eb4d7dc06ed6ae0f056794cf9138b6c4eca4b985b054e5d8d7ba676dd54', 'e6c179930cf4922fa6216f3644373ee12f9af1300df2f285e817778864f73907', 'user', '2019-10-24 17:36:38'),
(19, '0de7a18881659940626a4bdfc9a9c418578f19db9d769e3ee282f3aa183af836', '6de711709ced81124b92342a305cbbdbb8af90bc80ad02ff446014492748f70b', 'user', '2019-10-25 16:28:13'),
(19, '132ac6bcfa121fe041ddd2cd03a780855b062b5689c72d66ec19b0888669a17b', '9f4f094d232688929c58d191bfb99fa8bd923fbfc9cef7e6f1882035f6989aca', 'user', '2019-10-16 12:21:41'),
(19, '165748942d6cfc42e10275991bf284c2cc055466edca48a375f5193b454ac13a', 'ab82a6d8ac7d58884cfb204a7c884d89cb2e1f51fdeb8bd88a8030111ab20991', 'user', '2019-10-16 11:29:39'),
(19, '258932bfea6809d82ada5e44ce96ff7fc01835e91fe30df82f0ccacb4010957b', '0a706bbfbcce9c66c5cf16a34d93a81bbb2702b0505cf7b126c45bd93df0fff8', 'user', '2019-10-16 12:11:28'),
(19, '4e6bfe050ecf70fa5455f351a645aef5b12b9b32532d9056aa9fc755478135f0', 'f27ff867d88c54fbe13971dcafe5c32a6fc8d2e7b804545944842fabad3be434', 'user', '2019-11-15 16:46:16'),
(19, '7376edfb79e0f2ecd8e39204226b5db3217c2dd288629614a9c4a5c66c74fced', 'a46bbdb4cd45c019ebb708350046c3b2eb2546cd56b068da86fa0663d7cda408', 'user', '2019-10-16 14:09:30'),
(19, '8d3b2cb18857571121706e60a184ff38a22cc13da2212972ea900bb853c676dc', '54260f1f598799e2b45721092221ce4a95a4f67268c4ddfa5951b59dffb3822f', 'user', '2019-10-16 11:29:50'),
(19, 'aaf6d9693a1c1ee7456cf46f29e1004bbd0e631c766c0c24e595163d01896a26', '50012e7fdf0498863ad528f233f737305f9d2dc14dd47e7e033cd5db4c78b4c1', 'user', '2019-10-18 16:07:31'),
(19, 'afc4b603c428e939d2b2e0675e8fb29dd9983f996c42d986ff4c5b5d57a1be1a', 'e702a5a11fd00a6f1a344cce0de98d8f5f8173ce70dba55bf3e55484fc374b11', 'user', '2019-11-29 10:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `b_categories`
--

CREATE TABLE `b_categories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_categories`
--

INSERT INTO `b_categories` (`id`, `cat_name`, `alias`, `status`, `added_on`) VALUES
(1, 'Environment Clearance Related', 'environment-clearance-related', '1', '2018-05-19 16:24:51'),
(2, 'CTE Related', 'cte-related', '1', '2018-05-19 16:26:03'),
(3, 'CTO Related', 'cto-related', '1', '2019-07-15 15:36:08'),
(4, 'Compliance Related', 'compliance-related', '1', '2019-09-13 16:49:51'),
(5, 'Violation Related', 'violation-related', '1', '2019-09-13 16:49:57'),
(6, 'Enviro-Legal Related', 'enviro-legal-related', '1', '2019-09-13 16:50:05'),
(7, 'Green Building Related', 'green-building-related', '1', '2019-09-13 16:50:11'),
(8, 'NGT/MoEF&CC/SEAC/SEIAA Related', 'ngtmoefccseacseiaa-related', '1', '2019-09-13 16:50:18'),
(9, 'Others', 'others', '1', '2019-09-13 16:50:24');

-- --------------------------------------------------------

--
-- Table structure for table `b_contact_forms`
--

CREATE TABLE `b_contact_forms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `replied` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '1' COMMENT '0 for InActive,1 for Active,2 for Blocked,3 for Deleted',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `b_contact_forms`
--

INSERT INTO `b_contact_forms` (`id`, `name`, `email`, `contact_no`, `message`, `replied`, `status`, `added_on`) VALUES
(1, 'Eric Jones', 'eric@talkwithcustomer.com', '416-385-3200', 'Hi,\r\n\r\nMy name is Eric and I was looking at a few different sites online and came across your site grcgreentip.com.  I must say - your website is very impressive.  I am seeing your website on the first page of the Search Engine. \r\n\r\nHave you noticed that 70 percent of visitors who leave your website will never return?  In most cases, this means that 95 percent to 98 percent of your marketing efforts are going to waste, not to mention that you are losing more money in customer acquisition costs than you need to.\r\n \r\nAs a business person, the time and money you put into your marketing efforts is extremely valuable.  So why let it go to waste?  Our users have seen staggering improvements in conversions with insane growths of 150 percent going upwards of 785 percent. Are you ready to unlock the highest conversion revenue from each of your website visitors?  \r\n\r\nTalkWithCustomer is a widget which captures a website visitor’s Name, Email address and Phone Number and then calls you immediately, so that you can talk to the Lead exactly when they are live on your website — while they\'re hot! Best feature of all, International Long Distance Calling is included!\r\n  \r\nTry TalkWithCustomer Live Demo now to see exactly how it works.  Visit http://www.talkwithcustomer.com\r\n\r\nWhen targeting leads, speed is essential - there is a 100x decrease in Leads when a Lead is contacted within 30 minutes vs being contacted within 5 minutes.\r\n\r\nIf you would like to talk to me about this service, please give me a call.  We have a 14 days trial.  Visit http://www.talkwithcustomer.com to start converting up to 100X more leads today!\r\n\r\nThanks and Best Regards,\r\nEric\r\n\r\nIf you\'d like to unsubscribe go to http://liveserveronline.com/talkwithcustomer.aspx?d=grcgreentip.com\r\n', '0', '1', '2019-11-12 20:34:16'),
(2, 'Jorg Smithers', 'noreplymonkeydigital@gmai.com', '06-19413398', 'Get backlinks from websites which have Domain Authority above 50. Very rare and hard to get backlinks. Order today at a very low price, while the offer lasts.\r\n\r\nread more:\r\nhttps://www.monkeydigital.co/product/250-da-50-backlinks/\r\n\r\nthanks and regards\r\nMonkey Digital Team\r\nsupport@monkeydigital.co', '0', '1', '2019-11-19 20:26:16'),
(3, 'Joseph Goodell', 'noreplygooglealexarank@gmail.com', '69 786 42 34', 'Increase ranks and visibility for grcgreentip.com with a monthly SEO plan that is built uniquely for your website\r\n\r\nIncrease SEO metrics and ranks while receiving complete reports on monthly basis\r\n\r\nCheck out our plans\r\nhttps://googlealexarank.com/index.php/seo-packages/\r\n\r\nthanks and regards\r\nTop SEO Experts', '0', '1', '2019-11-25 23:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `b_contact_replys`
--

CREATE TABLE `b_contact_replys` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `reply_msg` text NOT NULL,
  `replied_on` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `b_email_templates`
--

CREATE TABLE `b_email_templates` (
  `id` int(11) NOT NULL,
  `email_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `email_subject` varchar(255) CHARACTER SET latin1 NOT NULL,
  `email_content` text NOT NULL,
  `email_keywords` varchar(255) CHARACTER SET latin1 NOT NULL,
  `template_type` enum('email','message_center') CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `b_email_templates`
--

INSERT INTO `b_email_templates` (`id`, `email_name`, `email_subject`, `email_content`, `email_keywords`, `template_type`) VALUES
(1, 'User Subscription', 'GreenTIP: User Subscription', '<p><font face="Cambria"><span style="font-size: 12px;">Dear Subscriber,</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">You have successfully subscribed at [WEBSITE_LINK] with email address [USER_EMAIL]</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">\r\nGreenTIP Team</span></font></p>\r\n', '[USER_EMAIL], [UNSUBSCRIBE_LINK], [WEBSITE_LINK]', 'email'),
(2, 'User Registration', 'GreenTIP: User Registration', '<p><font face="Cambria"><span style="font-size: 12px;">Dear [NAME],</span></font><span style="font-size: 12px;">﻿</span></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Thank you for your registration and we warmly welcome you to green tip.</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Please&nbsp; [VERIFY_LINK] to activate your account.</span><br></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">\r\nGreenTIP Team</span></font></p>', '[NAME], [EMAIL], [VERIFY_LINK]', 'email'),
(3, 'Activation Email', 'GreenTIP: Activation Email', '<p><font face="Cambria"><span style="font-size: 12px;">Dear [NAME],</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="color: rgb(85, 85, 85); font-size: 12px;">Your account has been activated. Thanks for joining us.&nbsp;</span><br></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">\r\nGreenTIP Team</span></font></p>', '[NAME]', 'email'),
(4, 'Reset Password', 'GreenTIP: We received a password reset request', '<h3 style="font-weight: 700; line-height: 30px; color: rgb(34, 34, 34); margin-top: 0px; margin-bottom: 0px; font-size: 20px;"><span style="font-size: 12px;"><font face="Cambria">We received a request to change your password</font></span></h3><p><span style="font-size: 12px;"><font face="Cambria">Dear [NAME],</font></span></p><p><font face="Cambria"><span style="color: rgb(34, 34, 34); font-size: 12px;">Use the link below to set up a new password for your account.</span><br></font></p><p><span style="font-size: 12px;"><font face="Cambria">Please [FORGOT_LINK] to reset password</font></span></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[NAME],[USER_EMAIL],[FORGOT_LINK]', 'email'),
(5, 'Enquiry / Contact', 'GreenTIP: Enquiry / Contact', '<p><font face="Cambria"><span style="font-size: 12px;">Dear Admin,</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">You have got query from</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Name : [NAME]</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Email : [EMAIL]</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Phone: [PHONE]</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Message : [MESSAGE]</span></font></p><span style="font-size: 12px;">\r\n</span><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span></font><span style="font-size: 12px;">﻿</span><font face="Cambria"><br><span style="font-size: 12px;">GreenTIP Team</span></font><br></p>', '[NAME],[EMAIL],[PHONE],[MESSAGE]', 'email'),
(6, 'Expert Registration', 'GreenTIP: Expert Registration', '<p><font face="Cambria"><span style="font-size: 12px;">Dear [NAME],</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Your profile has been created by admin</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Use the following values when prompted to login:</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Email: [USER_EMAIL]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Password: [PASSWORD]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Login Link: [LOGIN_LINK]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[NAME],[PASSWORD], [USER_EMAIL],[LOGIN_LINK]', 'email'),
(8, 'New Query', 'GreenTIP: New Query', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span style="color: rgb(34, 34, 34); font-size: 12px;" lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="">[RECEIVER_NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">You have received the new query from</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Sender </span><a href="Name:[SENDER_NAME]"><span style="font-size: 12px;">Name:[SENDER_NAME]</span></a></font></p><p><font face="Cambria"><span style="font-size: 12px;">Sender Email:[SENDER_EMAIL]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query Title: [QUERY]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME],[SENDER_EMAIL], [QUERY]', 'email'),
(9, 'Discarded Query by Admin', 'GreenTIP: Discarded Query by Admin', '<p><font face="Cambria"><span style="font-size: 12px;">Dear [RECEIVER_NAME]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Your query has been discarded by&nbsp;</span><a href="name:[SENDER_NAME]" style="background-color: rgb(255, 255, 255); color: rgb(20, 23, 25); outline: 0px;"><span style="font-size: 12px;">[SENDER_NAME]</span></a></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query Title: [QUERY]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME], [QUERY]', 'email'),
(10, 'Received Query By Admin', 'GreenTIP: Received Query By Admin', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span style="color: rgb(34, 34, 34); font-size: 12px;" lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="">[RECEIVER_NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Your have received the new query&nbsp; by&nbsp;</span><a href="name:[SENDER_NAME]" style="background-color: rgb(255, 255, 255); color: rgb(20, 23, 25); outline: 0px;"><span style="font-size: 12px;">[SENDER_NAME]</span></a><span style="font-size: 12px;">.Please respond for this,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query Title: [QUERY]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP&nbsp; Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME], [QUERY]', 'email'),
(11, 'Discarded Query by Expert', 'GreenTIP: Discarded Query by Expert', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span style="color: rgb(34, 34, 34); font-size: 12px;" lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="">[RECEIVER_NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query [QUERY] has been discarded by&nbsp;</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Expert Name: [SENDER_NAME]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Expert Email: [SENDER_EMAIL]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP&nbsp; Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME],[SENDER_EMAIL], [QUERY]', 'email'),
(12, 'Query Respond by Expert', 'GreenTIP: Query Respond by Expert', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span style="color: rgb(34, 34, 34); font-size: 12px;" lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="">[RECEIVER_NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query [QUERY] has been responded by&nbsp;</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Expert Name: [SENDER_NAME]</span><br></font></p><p><font face="Cambria"><span style="font-size: 12px;">Expert Email: [SENDER_EMAIL]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Respond Answer: [RESPOND_MESSAGE]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP&nbsp; Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME],[SENDER_EMAIL], [QUERY],[RESPOND_MESSAGE]', 'email'),
(13, 'Query Answer by Admin', 'GreenTIP: Query Answer by Admin', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span style="color: rgb(34, 34, 34); font-size: 12px;" lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="">[RECEIVER_NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">You have received the answer for this query by [SENDER_NAME].</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Query:[QUERY]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[RECEIVER_NAME],[SENDER_NAME],[SENDER_EMAIL], [QUERY]', 'email'),
(14, 'End User Registration By Admin', 'GreenTIP: End User Registration', '<p><font face="Cambria"><span style="font-size: 12px;">Dear [NAME],</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Your profile has been created by admin</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Use the following values when prompted to login:</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Email: [USER_EMAIL]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Password: [PASSWORD]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Login Link: [LOGIN_LINK]</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[NAME],[PASSWORD], [USER_EMAIL],[LOGIN_LINK]', 'email'),
(15, 'Bulk Email Template', 'GreenTIP: Bulk Email', '<p><font face="Cambria"><span style="font-size: 12px;">Dear&nbsp;</span><span lucida="" console",="" "courier="" new",="" monospace;="" font-size:="" 12px;="" white-space:="" pre-wrap;"="" style="color: rgb(34, 34, 34); font-size: 12px;">[NAME]</span><span style="font-size: 12px;">,</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">This is a bulk email regarding by greentip id</span></font></p><p><font face="Cambria"><span style="font-size: 12px;">Regards,</span><br><span style="font-size: 12px;">GreenTIP Team</span></font></p>', '[NAME]', 'email');

-- --------------------------------------------------------

--
-- Table structure for table `b_faqs`
--

CREATE TABLE `b_faqs` (
  `id` int(11) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_faqs`
--

INSERT INTO `b_faqs` (`id`, `question`, `answer`, `status`, `added_on`) VALUES
(1, 'What is GreenTIP?', '<p>What is GreenTIP?<br></p>', '1', '2019-06-22 09:10:08'),
(2, 'Who are the stakeholders who can use this portal?', '<p>Who are the stakeholders who can use this portal?<br></p>', '1', '2019-06-22 09:15:58'),
(3, 'What is the importance of this web site', '<p>This means green</p>', '3', '2019-07-11 11:00:06'),
(4, 'How one can subscribe to GreenTIP?', '<p>How one can subscribe to GreenTIP?<br></p>', '0', '2019-09-13 11:00:12'),
(5, 'Is there an Annual Subscription for GreenTIP?', '<p>Is there an Annual Subscription for GreenTIP?<br></p>', '0', '2019-09-13 11:00:25');

-- --------------------------------------------------------

--
-- Table structure for table `b_industrial_categories`
--

CREATE TABLE `b_industrial_categories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_industrial_categories`
--

INSERT INTO `b_industrial_categories` (`id`, `cat_name`, `alias`, `status`, `added_on`) VALUES
(10, 'Telecom', 'telecom', '1', '2019-09-17 16:23:41'),
(11, 'Construction', 'construction', '1', '2019-09-17 16:24:12'),
(12, 'Mining ', 'mining', '1', '2019-09-17 16:24:22'),
(13, 'Hospitality', 'hospitality', '1', '2019-09-17 16:24:33'),
(14, 'Real state', 'real-state', '1', '2019-09-17 16:24:50'),
(15, 'Manufacturing', 'manufacturing', '1', '2019-09-17 16:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `b_industrial_users`
--

CREATE TABLE `b_industrial_users` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `company_name` varchar(75) DEFAULT NULL,
  `contact_person` varchar(75) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `alternative_email` varchar(255) DEFAULT NULL,
  `contact_no` bigint(20) DEFAULT NULL,
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1','2','3') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_industrial_users`
--

INSERT INTO `b_industrial_users` (`id`, `category_id`, `company_name`, `contact_person`, `email`, `alternative_email`, `contact_no`, `created_on`, `updated_on`, `status`) VALUES
(25, 13, 'Triazinesoft', 'kapil jindal', 'kapilj@triazinesoft.com', 'karatrikjindal@gmail.com', 9785848396, '2019-09-17 17:29:43', '2019-09-17 18:22:21', '1');

-- --------------------------------------------------------

--
-- Table structure for table `b_login_attempts`
--

CREATE TABLE `b_login_attempts` (
  `id` mediumint(16) NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `attempts` tinyint(10) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1 = Admin, 2 = User'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_newsletters`
--

CREATE TABLE `b_newsletters` (
  `id` int(11) NOT NULL,
  `sent_to` enum('all','subscribers','emailid','notify_area') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `email_ids` text NOT NULL,
  `created_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_pages`
--

CREATE TABLE `b_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `content` longtext,
  `added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_pages`
--

INSERT INTO `b_pages` (`id`, `title`, `alias`, `content`, `added_on`) VALUES
(1, 'About us', 'about-us', '<p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">GRC GreenTIP (Technical Interactive Platform) is venture of GRC India, a pioneer environmental consultancy organization providing optimal solutions for Environmental Clearances for industrial as well as infrastructural projects.&nbsp;</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">In addition to the core business of providing consultancy related to government compliance for varying range of industries e.g. real estate, mines, refineries and much more, our key stakeholders come out with a thought to provide a digital and interactive platform where businesses can find technical know-how about prevailing laws to manage their compliances for Green tribunal authority and other government organizations which might affects their businesses at present or in long run.&nbsp;</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">Other than typical straight forward laws, this platform does provide expert help. Representative of a business organizations or research scholars can have paid subscription of the platform where they will have access to the expert panel for the compliance related problems.</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">This Platform provides input for EIA/EMP, CTE, CTO, STP, ETP, CRZ, Enviro-Legal and other related salient features.</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal"><br></p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal"><b>How it works?</b></p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">A users coming on this platform will have ample of information related to environmental issues and prevailing laws. At top of this if a users want get answer to some other specific business problem in this domain, s/he has to sign up and go for paid subscription, and once they are paid member they will have access to expert empanelled over platform.&nbsp;</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal">A paid member can write about their specific business problem within the TIP interface and he will be getting expert inputs for their environmental related issues in 3 to 5 business days.</p><p class="MsoNormal" style="margin-bottom:0in;margin-bottom:.0001pt;text-align:\r\njustify;line-height:normal"><br></p>', '2016-11-08 04:15:28'),
(2, 'Knowledge Center', 'knowledge-center', '<h3 style="margin: 15px 0px; padding: 0px; font-weight: 700; font-size: 14px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif;">The standard Lorem Ipsum passage, used since the 1500s</h3><p style="margin-bottom: 15px; padding: 0px; text-align: justify; font-family: &quot;Open Sans&quot;, Arial, sans-serif;">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>', '2016-11-08 08:35:28'),
(3, 'Privacy Policy', 'privacy-policy', '<p><span style="font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br></p>', '2016-11-09 08:35:28'),
(4, 'Terms & condition', 'terms-and-condition', '<p><span style="font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</span><br></p>', '2016-11-09 08:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `b_queries`
--

CREATE TABLE `b_queries` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `query` text,
  `status` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 for pending 1 for completed 2 for rejected',
  `added_on` datetime DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_queries`
--

INSERT INTO `b_queries` (`id`, `user_id`, `category_id`, `query`, `status`, `added_on`, `modified_on`) VALUES
(1, 19, 9, 'Test', 0, '2019-10-18 16:09:05', '2019-10-18 16:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `b_query_assign`
--

CREATE TABLE `b_query_assign` (
  `id` int(11) UNSIGNED NOT NULL,
  `query_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expert_answer` text,
  `answer` text,
  `added_on` datetime DEFAULT NULL,
  `respond_date` datetime DEFAULT NULL,
  `admin_review_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 for pending 1 for respond 2 for rejected',
  `review_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_query_assign`
--

INSERT INTO `b_query_assign` (`id`, `query_id`, `user_id`, `expert_answer`, `answer`, `added_on`, `respond_date`, `admin_review_date`, `status`, `review_status`) VALUES
(1, 1, 39, NULL, NULL, '2019-10-25 16:32:10', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `b_settings`
--

CREATE TABLE `b_settings` (
  `id` int(11) NOT NULL,
  `option_name` varchar(150) NOT NULL,
  `option_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_settings`
--

INSERT INTO `b_settings` (`id`, `option_name`, `option_value`) VALUES
(1, 'COMPANY', 'GreenTIP'),
(2, 'NOTIFICATION_EMAIL', 'sales@greentip.com'),
(3, 'NOTIFICATION_TITLE', 'GreenTIP'),
(4, 'CONTACT_NO', ' 0120-4044630, 4044660, 4323120'),
(5, 'CONTACT_EMAIL', 'contact@greentip.com'),
(6, 'CONTACT_ADDRESS', 'F-374 & 375, Sector–63, NOIDA–201 301 ,India'),
(8, 'NO_IMAGE', 'no-results.png'),
(9, 'ABOUT_HOME_PAGE', 'home page content');

-- --------------------------------------------------------

--
-- Table structure for table `b_sliders`
--

CREATE TABLE `b_sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '1' COMMENT '(0=> pending,1 => active,2=>blocked,3=>deleted)',
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_sliders`
--

INSERT INTO `b_sliders` (`id`, `title`, `image`, `status`, `added_on`) VALUES
(1, 'Slide 1', '54a68e2c844673a9b288b0512a3094cf.png', '1', '2019-06-22 14:19:43'),
(2, 'Slide 2', '31bc66e9caa9a7f43381c13720db8a93.png', '1', '2019-06-22 14:20:03'),
(3, 'Slide 3', 'dcefd665682e6f92c44cf74866dd2288.png', '1', '2019-06-22 14:20:18'),
(4, 'Slide 4', '01c06912ccf53ccf4a115dff64ec1118.png', '1', '2019-06-22 14:20:33'),
(5, 'Slide 5', '131ce8641745390f3ec769dcb5deac81.png', '1', '2019-06-22 14:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `b_subscriber`
--

CREATE TABLE `b_subscriber` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `added_on` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_subscriber`
--

INSERT INTO `b_subscriber` (`id`, `email`, `status`, `added_on`, `is_deleted`) VALUES
(1, 'rahulr@triazinesoft.com', 1, '2019-10-16 14:15:14', 0),
(2, 'ganeshd@triazinesoft.com', 1, '2019-10-16 14:16:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `b_users`
--

CREATE TABLE `b_users` (
  `id` int(11) NOT NULL,
  `role_type` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0 for admin 1 for expert 2 for end user',
  `name` varchar(75) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `contact_no` bigint(20) DEFAULT NULL,
  `description` text,
  `activation_code` varchar(255) DEFAULT NULL,
  `linkdin` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `status` enum('0','1','2','3') DEFAULT '0',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(32) DEFAULT NULL,
  `token` text,
  `last_login` datetime DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `education_qualification` varchar(255) NOT NULL,
  `expertise_field` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `b_users`
--

INSERT INTO `b_users` (`id`, `role_type`, `name`, `email`, `password`, `image`, `contact_no`, `description`, `activation_code`, `linkdin`, `facebook`, `status`, `created_on`, `updated_on`, `ip_address`, `token`, `last_login`, `username`, `company_name`, `occupation`, `education_qualification`, `expertise_field`) VALUES
(1, '0', 'Super Admin', 'admin@gmail.com', '$2a$08$m0z7qipR3LSfYZdVRmC9Ye/moZvxbGvgoWak10zky7aiRRNE1v9LC', '77c7b611d0155b0defa0dfcb0f3284ed.jpg', 9785848396, '', '2G7O68AG', NULL, NULL, '1', '2019-06-18 16:04:40', '2019-06-18 16:19:48', '164.100.222.44', NULL, '2019-11-29 10:55:46', 'administrator', '', '', '', ''),
(19, '2', 'kapil triazine', 'kapilj@triazinesoft.com', '$2a$08$D23ieH/lKlRrWdIdc06A0uTYVDvGSenSt9TsoA.xabmVkEuzmbM3K', '6fd45c7ba562d178f2983cb47269c7f9.jpg', 9785848396, '', '754727U2', NULL, NULL, '1', '2019-07-05 06:32:15', '2019-07-05 06:32:15', '164.100.222.44', NULL, '2019-11-29 10:53:37', NULL, 'Triazinesoft', 'Job', 'B.TECH', 'PHP'),
(21, '1', 'expert vijay', 'evijay@gmail.com', '$2a$08$187zebs5CyXpdgk4PM/LHeyrngIQpi9NbE/hEmyffN.WPaz9yARFm', '5d5c95ab7e687f1dc3a30b158fac8578.png', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nWhy do we use it?\r\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', NULL, '', '', '1', '2019-07-05 06:48:34', '2019-07-05 06:48:34', '164.100.222.44', NULL, '2019-10-15 18:44:26', NULL, 'Triazinesoft', 'Job', 'B.TECH', 'PHP'),
(36, '1', 'Mayank Gautam', 'mayankg@triazinesoft.com', '$2a$08$m5xINZgtougXvIGbVZXaEuLFSCLdzP/5oaK9EZuxD9F86JoGTecVm', '3691aa6883752ff5a5df09171f19c619.jpg', 9643367110, 'Expert', '', '', '', '1', '2019-07-15 07:04:06', '2019-07-15 07:04:06', '119.82.68.242', NULL, '2019-07-15 13:16:01', NULL, '', '', '', ''),
(37, '2', 'Ramesh mishra', 'ganeshd@triazinesoft.com', '$2a$08$Olj/Fm.cAjdXPL.kfWi.Ce1e8ASf9rTRWE2fI4k0VVqHzv.5gdODO', NULL, NULL, NULL, '', NULL, NULL, '1', '2019-07-26 07:44:10', '2019-07-26 07:44:10', NULL, NULL, NULL, NULL, '', '', '', ''),
(38, '2', 'Umang Mathur', 'umang.mathur@grc-india.com', '$2a$08$.s70YfmM39gizNi7WmCrle5wb/m1H4on2sfbLDEChGbjTIcdCbzq2', NULL, NULL, NULL, 'M2L8Y9J8', NULL, NULL, '1', '2019-08-09 13:37:49', '2019-08-09 13:37:49', '115.112.119.45', NULL, '2019-08-09 19:08:55', NULL, '', '', '', ''),
(39, '1', 'Dr.  Dhiraj Singh', 'md@grc-india.com', '$2a$08$xIuP0Op/Wr8mGysj5fq5MeYUY5HIG5n11UZalhoLC6EVzVcrIdxEG', NULL, NULL, 'Environmental science professional', NULL, '', '', '1', '2019-08-09 13:47:00', '2019-08-09 13:47:00', NULL, NULL, NULL, NULL, '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `b_auto_login`
--
ALTER TABLE `b_auto_login`
  ADD PRIMARY KEY (`user`,`series`);

--
-- Indexes for table `b_categories`
--
ALTER TABLE `b_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_contact_forms`
--
ALTER TABLE `b_contact_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_contact_replys`
--
ALTER TABLE `b_contact_replys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_email_templates`
--
ALTER TABLE `b_email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_faqs`
--
ALTER TABLE `b_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_industrial_categories`
--
ALTER TABLE `b_industrial_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_industrial_users`
--
ALTER TABLE `b_industrial_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_login_attempts`
--
ALTER TABLE `b_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_newsletters`
--
ALTER TABLE `b_newsletters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_pages`
--
ALTER TABLE `b_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_queries`
--
ALTER TABLE `b_queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_query_assign`
--
ALTER TABLE `b_query_assign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_settings`
--
ALTER TABLE `b_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_sliders`
--
ALTER TABLE `b_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_subscriber`
--
ALTER TABLE `b_subscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_users`
--
ALTER TABLE `b_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `b_categories`
--
ALTER TABLE `b_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `b_contact_forms`
--
ALTER TABLE `b_contact_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `b_contact_replys`
--
ALTER TABLE `b_contact_replys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `b_email_templates`
--
ALTER TABLE `b_email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `b_faqs`
--
ALTER TABLE `b_faqs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `b_industrial_categories`
--
ALTER TABLE `b_industrial_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `b_industrial_users`
--
ALTER TABLE `b_industrial_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `b_login_attempts`
--
ALTER TABLE `b_login_attempts`
  MODIFY `id` mediumint(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `b_newsletters`
--
ALTER TABLE `b_newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `b_pages`
--
ALTER TABLE `b_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `b_queries`
--
ALTER TABLE `b_queries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `b_query_assign`
--
ALTER TABLE `b_query_assign`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `b_settings`
--
ALTER TABLE `b_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `b_sliders`
--
ALTER TABLE `b_sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `b_subscriber`
--
ALTER TABLE `b_subscriber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `b_users`
--
ALTER TABLE `b_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
