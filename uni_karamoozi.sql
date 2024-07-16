-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2024 at 11:15 AM
-- Server version: 8.0.37-0ubuntu0.22.04.3
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uni_karamoozi`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_token`
--

CREATE TABLE `auth_token` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` text NOT NULL,
  `type` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `expire_date` timestamp NULL DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `auth_token`
--

INSERT INTO `auth_token` (`id`, `user_id`, `token`, `type`, `status`, `expire_date`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(90, 4, '342f1e86c140d8da5046194db02c620563746f6b656e', 1, 0, '2024-07-14 15:36:07', '2024-07-12 19:06:07', '2024-07-12 19:06:07', NULL, NULL),
(91, 4, '348ef708ade3788c828305ccb591e6fb82746f6b656e', 1, 0, '2024-07-14 15:36:48', '2024-07-12 19:06:48', '2024-07-12 19:06:48', NULL, NULL),
(92, 4, '34af00029ba6f5a31f1b59b8954657620d746f6b656e', 1, 0, '2024-07-14 15:37:51', '2024-07-12 19:07:51', '2024-07-12 19:07:51', NULL, NULL),
(93, 4, '34218cc7b61a4405da07b715a2be5ba3ec746f6b656e', 1, 0, '2024-07-14 15:39:12', '2024-07-12 19:09:12', '2024-07-12 19:09:12', NULL, NULL),
(94, 4, '344b3f649d4bed5cc90a3d39961170f9bf746f6b656e', 1, 0, '2024-07-14 15:44:37', '2024-07-12 19:14:37', '2024-07-12 19:14:37', NULL, NULL),
(96, 4, '34581ffc8418e426af956a40b37377c4cd746f6b656e', 1, 0, '2024-07-14 15:49:19', '2024-07-12 19:19:19', '2024-07-12 19:19:19', NULL, NULL),
(98, 4, '34a7223d7269a33501b13f48e817b80403746f6b656e', 1, 0, '2024-07-14 15:53:42', '2024-07-12 19:23:42', '2024-07-12 19:23:42', NULL, NULL),
(101, 4, '3408a405678567f1ffa3a3df745c6ddce5746f6b656e', 1, 0, '2024-07-15 18:42:23', '2024-07-13 22:12:23', '2024-07-13 22:12:23', NULL, NULL),
(102, 4, '34cd0de9f87c4c893b2c92aca12c3e748c746f6b656e', 1, 0, '2024-07-16 10:54:53', '2024-07-14 14:24:53', '2024-07-14 14:24:53', NULL, NULL),
(105, 4, '349bc070169a085923dda22bab314281f2746f6b656e', 1, 0, '2024-07-17 04:58:31', '2024-07-15 08:28:31', '2024-07-15 08:28:31', NULL, NULL),
(106, 4, '34b448d7cb18e074eb4651698a1c608b0a746f6b656e', 1, 0, '2024-07-17 14:36:27', '2024-07-15 18:06:27', '2024-07-15 18:06:27', NULL, NULL),
(107, 4, '3471e6be1cb2ce5fa9e5886a4d04f53c21746f6b656e', 1, 0, '2024-07-17 14:52:11', '2024-07-15 18:22:11', '2024-07-15 18:22:11', NULL, NULL),
(109, 4, '341bdc8ff69243abfd39f2a0474ec52ce7746f6b656e', 1, 0, '2024-07-18 02:21:44', '2024-07-16 05:51:44', '2024-07-16 05:51:44', NULL, NULL),
(111, 4, '344cf06e5f80a4a4833024bfe3d5ddb0b3746f6b656e', 1, 0, '2024-07-18 04:08:03', '2024-07-16 07:38:03', '2024-07-16 07:38:03', NULL, NULL),
(112, 4, '349a8346443036664c6c3e2262510828ca746f6b656e', 1, 0, '2024-07-18 04:17:08', '2024-07-16 07:47:08', '2024-07-16 07:47:08', NULL, NULL),
(113, 4, '3457002bff8c3a9f538ce18496c9cda1aa746f6b656e', 1, 0, '2024-07-18 04:20:57', '2024-07-16 07:50:57', '2024-07-16 07:50:57', NULL, NULL),
(115, 4, '34a73e3f707dad1050918ab6e13a131a06746f6b656e', 1, 0, '2024-07-18 06:11:01', '2024-07-16 09:41:01', '2024-07-16 09:41:01', NULL, NULL),
(117, 4, '34e700fdab1a96774d79564c7d38bd1450746f6b656e', 1, 0, '2024-07-18 07:25:34', '2024-07-16 10:55:34', '2024-07-16 10:55:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int NOT NULL,
  `province_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `company_registration_application`
--

CREATE TABLE `company_registration_application` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_manager_name` varchar(255) DEFAULT NULL,
  `company_supervisor_name` varchar(255) DEFAULT NULL,
  `company_supervisor_phone` varchar(255) DEFAULT NULL,
  `company_telephone` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `company_registration_application`
--

INSERT INTO `company_registration_application` (`id`, `user_id`, `company_name`, `company_manager_name`, `company_supervisor_name`, `company_supervisor_phone`, `company_telephone`, `company_address`, `description`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(9, 4, 'پویا گام خراسان', 'سجاد پاسبان رضوی', 'سجاد پاسبان رضوی', '9374812890', '5137263125', 'asd', 'asd', 1, '2024-07-04 17:18:22', '2024-07-04 17:18:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `content` text,
  `banner_path` varchar(255) DEFAULT NULL,
  `has_star` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `seo_description`, `content`, `banner_path`, `has_star`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(19, 'افتخار آفرینی دانشگاه ملی مهارت استان خراسان رضوی در اولین المپیاد ورزش همگانی دانشگاه ملی مهارت', 'دانشگاه ملی مهارت استان خراسان رضوی در اولین المپیاد ورزش همگانی دانشگاه ملی مهارت مقام قهرمانی رشته پرتاب دارت و مقام نایب قهرمانی رشته آمادگی جسمانی را کسب کرد.', '<div>\r\n<p style=\"text-align: justify;\">دانشگاه ملی مهارت استان خراسان رضوی در این المپیاد در دو رشته آمادگی جسمانی و پرتاب دارت شرکت کرد. در رشته پرتاب دارت، صدرا بنایی، سیدمحمد صالح و علیرضا خرمشاهی با مربیگری مجتبی طاووسی موفق به کسب عنوان نخست شدند. در مسابقات تیمی آمادگی جسمانی، بهنام قاسمی، یاسین خداشاهی و محمدمهدی درودی با مربیگری محسن امیری احمد آباد موفق به کسب مقام دوم شدند. همچنین در مسابقات انفرادی در رشته آمادگی جسمانی یاسین خداشاهی و در رشته پرتاب دارت صدرا بنایی مقام سوم را کسب کردند.</p>\r\n</div>', 'storage/content/content-2024-07-06-14-05-58.webp', 1, 1, '2024-07-06 14:05:58', '2024-07-06 14:05:58', NULL, NULL),
(20, 'برگزاری مراسم بزرگداشت چهلمین روز شهادت شهید جمهور و شهدای همراه', 'مراسم بزرگداشت چهلمین روز شهادت آیت الله رئیسی و همراهان با سخنرانی نماینده نهاد رهبری در دانشگاه فرهنگیان برگزار شد', '<p style=\"text-align: justify;\">روز چهارشنبه ۶ تیرماه ۱۴۰۳ به همت معاونت فرهنگی و اجتماعی دانشگاه ملی مهارت استان خراسان رضوی، با سخنرانی حجت الاسلام والمسلمین حاج آقای میرزایی نماینده محترم نهاد رهبری در دانشگاه فرهنگیان و با حضور دانشجویان، اساتید و کارکنان مراسم بزرگداشت شهید جمهور، آیت الله رئیسی و برگزار شد.</p>\r\n<div>\r\n<div>\r\n<p style=\"text-align: justify;\">حاج آقای میرزایی در سخنان خود در این مراسم گفتند: خدا را شکر می کنیم که به همان مقدار که توفیق داشتیم از وجود پربرکت حضرت آیت الله رئیسی بهره مند شدیم و از خدا بخواهیم و دعا کنیم که 8 تیرماه رئیس جمهوری مانند آیت الله رئیسی یا بهتر از ایشان به ما عطا فرماید. ایشان ضمن اشاره به انتخابات پیش رو انتخابات را یک هدیه الهی به مردم دانستند که چهل یا پنجاه سال پیش نداشته اند و اشاره کردند که مردم در گذشته نقشی در حکومت و اداره جامعه نداشته اند و گفتند: خداوند به برکت انقلاب اسلامی هدیه ای به نام انقلاب و زیر سایه انقلاب، هدیه ای به نام انتخاب به ما داده است. با انتخاب رئیس جمهور چندسال سرنوشت ملت را به دست ایشان خواهیم سپرد و در روز قیامت یکی از مواردی که از انسان بازخواست می شود انتخاب های اوست. انتخاب هم لطف خدا، هم حق ما و هم وظیفه ماست و ما حق شرکت در انتخابات را نداریم. ایشان در بیانات خود توضیح دادند که شرکت نکردن در انتخابات نیز خود نوعی شرکت کردن است و چنانچه با عدم شرکت موجب شدیم که دیگران انتخاب بدی داشته باشند در قبال آن انتخاب بد مسئول هستیم و باید سعی کنیم هم بهترین را انتخاب کنیم و به به دیگران معرفی کنیم و چنانچه از دیگران برای شرکت در انتخابات دعوت کنیم، دعوت به حق کرده ایم. ایشان با اشاره به آیات قرآن توضیح دادند که خداوند ملاک هایی برای انتخاب مشخص کرده که از جمله آنها قوی و امین بودن است و با بررسی روزمه کاری کاندیداها می توان این معیارها را در ایشان تشخیص داد و اصلح را انتخاب کرد.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<div>&nbsp;</div>\r\n</div>\r\n<p>&nbsp;</p>\r\n<div>&nbsp;</div>\r\n</div>', 'storage/content/content-2024-07-06-14-07-49.webp', 1, 1, '2024-07-06 14:07:49', '2024-07-06 14:07:49', NULL, NULL),
(21, 'اولین دوره مسابقات برنامه نویسی codestorm', 'اولین دوره مسابقات برنامه نویسی codestorm برگزار می گردد.', '<p><u><strong>انجمن&zwnj;های علمی کامپیوتر دانشگاه فنی و حرفه&zwnj;ای مشهد با همکاری گروه نرم&zwnj;افزاری پارت برگزار می&zwnj;کنند</strong></u></p>\r\n<p>نخستین مسابقه برنامه&zwnj;نویسی ویژه دانشجویان دانشگاه&zwnj; فنی و حرفه&zwnj;ای استان خراسان رضوی</p>\r\n<p>کلیه&zwnj;ی دانشجویانی که با مبانی برنامه&zwnj;سازی آشنا هستند می&zwnj;توانند در این مسابقه شرکت کرده و آموخته&zwnj;های خود را محک بزنند</p>\r\n<p>ثبت نام در مسابقات به صورت کاملا رایگان است</p>\r\n<p>قواعد شرکت در مسابقه: ثبت&zwnj;نام صرفا در قالب تیم&zwnj;های سه نفره شرکت در مسابقه صرفا برای دانشجویان دانشگاه فنی و حرفه ای خراسان رضوی مجاز می&zwnj;باشد.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>جوایز مسابقه:</strong></p>\r\n<ul>\r\n<li>تیم اول: 15 میلیون تومان</li>\r\n<li>تیم دوم: 10 میلیون تومان</li>\r\n<li>تیم سوم: 5 میلیون تومان</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<p>ثبت&zwnj;نام از جمعه 14 اردیبهشت الی دوشنبه 31 اردیبهشت 1403</p>\r\n<p>جهت کسب اطلاعات بیشتر و آشنایی با نحوه ثبت&zwnj;نام به نشانی زیر مراجعه کنید:</p>\r\n<p><a href=\"https://part.institute/events/codestorm/\" target=\"_blank\" rel=\"nofollow noreferrer noopener\">https://part.institute/events/codestorm/</a></p>\r\n<p>&nbsp;</p>\r\n<p><a>#CodeStorm</a>&nbsp;<a>#CollegiateProgrammingContest</a></p>\r\n<p><a href=\"https://t.me/CSTVU\" target=\"_blank\" rel=\"nofollow noreferrer noopener\">https://t.me/CSTVU</a></p>\r\n<p><a href=\"https://t.me/CSTVUSupport\" target=\"_blank\" rel=\"nofollow noreferrer noopener\">https://t.me/CSTVU_Support</a></p>\r\n<p><a href=\"https://t.me/partsoftwaregroup\" target=\"_blank\" rel=\"nofollow noreferrer noopener\">https://t.me/partsoftwaregroup</a></p>\r\n<p><a href=\"https://t.me/part_institute\" target=\"_blank\" rel=\"nofollow noreferrer noopener\">https://t.me/part_institute</a></p>', 'storage/content/content-2024-07-06-14-10-44.webp', 0, 1, '2024-07-06 14:10:44', '2024-07-06 14:10:44', NULL, NULL),
(22, 'بازدید دکتر اسفندیار از مرکز رشد دانشکده فنی شهید منتظری', 'دکتر اسفندیار، مدیر مراکز رشد دانشگاه فنی و حرفه ای از مرکز رشد دانشکده فنی شهید منتظری مشهد بازدید کردند.', '<div>\r\n<p>روز دوشنبه ۹بهمن ماه، جناب آقای دکتر اسفندیار، مدیر محترم مراکز رشد دانشگاه فنی و حرفه ای، از مرکز رشد دانشکده فنی شهید منتظری مشهد بازدید داشتند. در این دیدار صمیمانه، مدیران واحدها و هسته های مرکز رشد مشکلات و درخواست های خود را با ایشان در میان گذاشتند.</p>\r\n</div>', 'storage/content/content-2024-07-06-14-11-47.webp', 1, 1, '2024-07-06 14:11:47', '2024-07-06 14:11:47', NULL, NULL),
(23, 'برگزاری جلسه توجیهی دانشجویان ورودی جدید مقاطع کاردانی و کارشناسی دانشکده فنی شهید منتظری مشهد', 'جلسه توجیهی دانشجویان کاردانی و کارشناسی پیوسته در سالن همایش دانشکده فنی شهید منتظری مشهد برگزار شد.', '<div>\r\n<p style=\"text-align: justify; line-height: 2;\">به&nbsp;گزارش روابط عمومی دانشگاه فنی و حرفه ای واحدخراسان رضوی، جلسه توجیهی دانشجویان جدید الورودکاردانی و کارشناسی پیوسته برگزار شد. در این جلسه ابتدا حجت الاسلام والمسلمین علیزاده موسوی، مسئول محترم دفترنهاد مقام معظم رهبری در دانشگاه فنی و حرفه ای خراسان رضوی سخنانی درباره معنویت در زندگی همراه با استفاده از تجارب ارزنده دیگران، همچنین استفاده از فرصت ها و ظرفیت های جوانی در شرایط و موقعیت جوانی اظهار داشتند. در ادامه بیاناتشان، توجه خاص به حضور اکثریتی دانشجویان در روز 13 آبان و اعلام مبارزه با استکبارجهانی تاکید فرمودند. سخنران بعدی جلسه دکتر خاتمی، ریاست محترم دانشگاه فنی و حرفه ای خراسان رضوی بودند. ایشان با محکوم کردن جنایات جنگی رژیم غاصب اسرائیل و کشتار مردم مظلوم فلسطین غزه، مطالبی درباره اهداف و رسالت دانشگاه فنی و حرفه ای و توجه دانشجویان به اهمیت تحصیل در دانشگاه و تبدیل علم به ثروت، کسب تخصص در مسیر تحصیل و تلاش برای اینکه فردی موثر در جامعه باشند مطالبی بیان داشتند.&nbsp;ایشان تحصیل در دانشگاه را فرصتی با ارزش برای افزایش اندوخته های مهارتی که زمینه اشتغال آنها در جامعه را افزایش می دهد اشاره داشتند. دکتر خاتمی در پایان صحبتهایشان توصیه اکید به دانشجویان عزیز داشتند که در دوران تحصیل حضور در انجمن های علمی و تشکل های اجتماعی به عنوان ارتباطات با جامعه و صنعت می تواند بستری برای رسیدن به ایده ها و خلاقیتها در جهت کار آفرینی برای باشد و از آن به عنوان یک تجربه مفید همراه با درس بهره مند شوند. ودر ادامه معاونت آموزشی، دکتر نبوی، معاونت فرهنگی- دانشجویی، دکتر احمدی، معاون پژوهشی و فناوری،دکتر بهلوری و همچنین آقای رضوی ،مسئول محترم حراست توضیحاتی در خصوص حوزه های خود به دانشجویان و والدین حاضر در جلسه بیان نمودند.</p>\r\n<p>&nbsp;</p>\r\n<div>&nbsp;</div>\r\n</div>', 'storage/content/content-2024-07-06-14-13-01.webp', 1, 1, '2024-07-06 14:13:01', '2024-07-06 14:13:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `degree`
--

CREATE TABLE `degree` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `intern_recruitment_application`
--

CREATE TABLE `intern_recruitment_application` (
  `id` int NOT NULL,
  `code` varchar(255) NOT NULL,
  `semester_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cra_id` int NOT NULL,
  `group_id` int NOT NULL,
  `capacity` int NOT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `key_param` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `priority` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `title`, `path`, `key_param`, `logo`, `parent_id`, `priority`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(10, 'داشبورد', '/', 'dashboard', 'pi pi-gauge', -1, 0, 1, '2024-06-14 08:54:59', '2024-06-14 08:54:59', NULL, NULL),
(11, 'مدیریت جداول پایه', '/base-tables-management', 'base-tables-management', 'pi pi-cog', -1, 1, 1, '2024-06-14 09:08:52', '2024-06-14 09:08:52', NULL, NULL),
(13, 'مدیریت محتوا', '/content-management', 'content-management', 'pi pi-file', -1, 0, 1, '2024-06-14 09:22:30', '2024-06-14 09:22:30', NULL, NULL),
(14, 'مدیریت گروه ها', '/group-management', 'group-management', 'pi pi-th-large', -1, 0, 1, '2024-06-30 19:09:26', '2024-06-30 19:09:26', NULL, NULL),
(15, 'مدیریت شرکت ها', '/company-registration-application-management', 'company-registration-application-management', 'pi pi-building', -1, 0, 1, '2024-07-02 20:27:59', '2024-07-02 20:27:59', NULL, NULL),
(16, 'درخواست ثبت شرکت', '/company-registration-application', 'company-registration-application', 'pi pi-pen-to-square', -1, 0, 1, '2024-07-02 20:45:57', '2024-07-02 20:45:57', NULL, NULL),
(17, 'درخواست جذب کارآموز', '/intern-recruitment-application', 'intern-recruitment-application', 'pi pi-pen-to-square', -1, 0, 1, '2024-07-04 18:03:05', '2024-07-04 18:03:05', NULL, NULL),
(18, 'مدیریت جذب کارآموز', '/intern-recruitment-application-management', 'intern-recruitment-application-management', 'pi pi-user', -1, 0, 1, '2024-07-04 20:45:23', '2024-07-04 20:45:23', NULL, NULL),
(19, 'مدیریت نیمسال های تحصیلی', '/semester-management', 'semester-management', 'pi pi-calendar-clock', -1, 0, 1, '2024-07-04 20:48:47', '2024-07-04 20:48:47', NULL, NULL),
(20, 'مدیریت سایر سایت ها', '/sites-management', 'sites-management', 'pi pi-link', -1, 0, 1, '2024-07-04 22:51:57', '2024-07-04 22:51:57', NULL, NULL),
(21, 'مدیریت ارتباط با ما', '/contactus-management', 'contactus-management', 'pi pi-envelope', -1, 0, 1, '2024-07-04 22:57:49', '2024-07-04 22:57:49', NULL, NULL),
(22, 'مدیریت کاربران و گروه ها', '/users-groups-management', 'users-groups-management', 'pi pi-expand', -1, 0, 1, '2024-07-07 17:29:02', '2024-07-07 17:29:02', NULL, NULL),
(23, 'مدیریت دانشجویان', '/stu-semesters-management', 'stu-semesters-management', 'pi pi-user-plus', -1, 0, 1, '2024-07-07 20:45:03', '2024-07-07 20:45:03', NULL, NULL),
(24, 'کانورت اطلاعات', '/cvt-management', 'cvt-management', 'pi pi-server', -1, 0, 1, '2024-07-09 03:48:37', '2024-07-09 03:48:37', NULL, NULL),
(25, 'مدیریت درخواست های شرکت', '/cr-management', 'cr-management', 'pi pi-file-plus', -1, 0, 1, '2024-07-12 16:30:36', '2024-07-12 16:30:36', NULL, NULL),
(26, 'درخواست ثبت کارآموزی', '/stu-request', 'stu-request', 'pi pi-spinner-dotted', -1, 0, 1, '2024-07-14 15:04:05', '2024-07-14 15:04:05', NULL, NULL),
(27, 'مدیریت درخواست کارآموزی', '/stu-request-management', 'stu-request-management', 'pi pi-inbox', -1, 0, 1, '2024-07-15 18:30:12', '2024-07-15 18:30:12', NULL, NULL),
(28, 'دانشجو های من', '/my-students', 'my-students', 'pi pi-person', -1, 0, 1, '2024-07-16 09:51:21', '2024-07-16 09:51:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(2, 'کاربر ساده', 1, '2024-06-06 09:13:17', '2024-06-06 09:13:17', NULL, NULL),
(3, 'مدیر سامانه', 1, '2024-06-06 09:13:37', '2024-06-06 09:13:37', NULL, NULL),
(4, 'دانشجو', 1, '2024-06-06 09:14:11', '2024-06-06 09:14:11', NULL, NULL),
(5, 'استاد', 1, '2024-06-06 09:14:37', '2024-06-06 09:14:37', NULL, NULL),
(6, 'مدیر گروه', 1, '2024-06-06 09:14:57', '2024-06-06 09:14:57', NULL, NULL),
(7, 'کارفرما', 1, '2024-06-06 09:15:25', '2024-06-06 09:15:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles_access`
--

CREATE TABLE `roles_access` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `roles_access`
--

INSERT INTO `roles_access` (`id`, `role_id`, `menu_id`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(26, 2, 10, 1, '2024-07-04 17:46:00', '2024-07-04 17:46:00', NULL, NULL),
(27, 3, 10, 1, '2024-07-04 17:46:18', '2024-07-04 17:46:18', NULL, NULL),
(28, 4, 10, 1, '2024-07-04 17:46:23', '2024-07-04 17:46:23', NULL, NULL),
(29, 5, 10, 1, '2024-07-04 17:46:37', '2024-07-04 17:46:37', NULL, NULL),
(30, 6, 10, 1, '2024-07-04 17:46:40', '2024-07-04 17:46:40', NULL, NULL),
(31, 7, 10, 1, '2024-07-04 17:46:49', '2024-07-04 17:46:49', NULL, NULL),
(32, 2, 16, 1, '2024-07-04 17:47:08', '2024-07-04 17:47:08', NULL, NULL),
(33, 3, 11, 1, '2024-07-04 17:47:28', '2024-07-04 17:47:28', NULL, NULL),
(34, 3, 14, 1, '2024-07-04 17:47:46', '2024-07-04 17:47:46', NULL, NULL),
(36, 3, 13, 1, '2024-07-04 17:48:08', '2024-07-04 17:48:08', NULL, NULL),
(37, 3, 15, 1, '2024-07-04 17:48:13', '2024-07-04 17:48:13', NULL, NULL),
(38, 7, 17, 1, '2024-07-04 18:04:21', '2024-07-04 18:04:21', NULL, NULL),
(39, 3, 19, 1, '2024-07-04 21:23:41', '2024-07-04 21:23:41', NULL, NULL),
(40, 3, 20, 1, '2024-07-04 23:29:11', '2024-07-04 23:29:11', NULL, NULL),
(41, 3, 21, 1, '2024-07-05 06:59:27', '2024-07-05 06:59:27', NULL, NULL),
(42, 3, 22, 1, '2024-07-07 17:29:32', '2024-07-07 17:29:32', NULL, NULL),
(43, 3, 23, 1, '2024-07-07 20:45:45', '2024-07-07 20:45:45', NULL, NULL),
(45, 3, 24, 1, '2024-07-09 03:49:00', '2024-07-09 03:49:00', NULL, NULL),
(46, 6, 25, 1, '2024-07-12 16:31:26', '2024-07-12 16:31:26', NULL, NULL),
(47, 4, 26, 1, '2024-07-14 15:05:23', '2024-07-14 15:05:23', NULL, NULL),
(48, 5, 27, 1, '2024-07-15 18:30:42', '2024-07-15 18:30:42', NULL, NULL),
(49, 6, 27, 1, '2024-07-15 18:30:59', '2024-07-15 18:30:59', NULL, NULL),
(50, 7, 28, 1, '2024-07-16 09:51:38', '2024-07-16 09:51:38', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int NOT NULL,
  `code` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int NOT NULL,
  `uni_name` varchar(255) DEFAULT NULL,
  `uni_logo_path` varchar(255) DEFAULT NULL,
  `footer_description` text,
  `location` json DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text,
  `register_rules` text,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `uni_name`, `uni_logo_path`, `footer_description`, `location`, `telephone`, `email`, `fax`, `address`, `description`, `register_rules`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'دانشگاه فنی و حرفه ای منتظری', 'storage/logo/site_logo.png', 'تمامی حقوق مادی و معنوی سایت متعلق به وزارت علوم می باشد', '{\"lat\": \"36.2829981\", \"long\": \"59.5481874\"}', '05137263125', 's.pr98@yahoo.com', '05137263125', 'asdasd', 'sds', 'aasdasd', 1, '2024-05-31 08:36:31', '2024-05-31 08:36:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sites_management`
--

CREATE TABLE `sites_management` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `sites_management`
--

INSERT INTO `sites_management` (`id`, `name`, `link`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(2, 'سامانه بوستان', 'https://bustan.tvu.ac.ir', 1, '2024-07-04 23:40:02', '2024-07-04 23:40:02', NULL, NULL),
(3, 'سایت ثبت پروژه', 'https://project.sapd.ir', 1, '2024-07-06 13:27:12', '2024-07-06 13:27:12', NULL, NULL),
(4, 'سایت دانشگاه', 'https://montazeri.tvu.ac.ir', 1, '2024-07-06 13:27:39', '2024-07-06 13:27:39', NULL, NULL),
(5, 'سایت گروه کامپیوتر', 'https://mtc.sapd.ir', 1, '2024-07-06 13:29:17', '2024-07-06 13:29:17', NULL, NULL),
(6, 'سایت تغذیه', 'https://saba.tvu.ac.ir', 1, '2024-07-06 13:29:53', '2024-07-06 13:29:53', NULL, NULL),
(7, 'سایت سماد', 'https://samad.tvu.ac.ir', 1, '2024-07-06 13:30:21', '2024-07-06 13:30:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stu_request`
--

CREATE TABLE `stu_request` (
  `id` int NOT NULL,
  `code` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `group_id` int NOT NULL,
  `stu_semester_id` int NOT NULL,
  `teacher` int NOT NULL,
  `type` int NOT NULL,
  `ira_id` int DEFAULT NULL,
  `intern_name` varchar(255) NOT NULL,
  `intern_phone` varchar(255) NOT NULL,
  `intern_telephone` varchar(255) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `place_name` varchar(255) DEFAULT NULL,
  `place_telephone` varchar(255) DEFAULT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `supervisor_phone` varchar(255) DEFAULT NULL,
  `sat` tinyint(1) DEFAULT NULL,
  `sat_from` time DEFAULT NULL,
  `sat_to` time DEFAULT NULL,
  `sun` tinyint(1) DEFAULT NULL,
  `sun_from` time DEFAULT NULL,
  `sun_to` time DEFAULT NULL,
  `mon` tinyint(1) DEFAULT NULL,
  `mon_from` time DEFAULT NULL,
  `mon_to` time DEFAULT NULL,
  `tue` tinyint(1) DEFAULT NULL,
  `tue_from` time DEFAULT NULL,
  `tue_to` time DEFAULT NULL,
  `wed` tinyint(1) DEFAULT NULL,
  `wed_from` time DEFAULT NULL,
  `wed_to` time DEFAULT NULL,
  `thu` tinyint(1) DEFAULT NULL,
  `thu_from` time DEFAULT NULL,
  `thu_to` time DEFAULT NULL,
  `description` text,
  `teacher_confirm` tinyint(1) DEFAULT NULL,
  `teacher_description` text,
  `manager_confirm` tinyint(1) DEFAULT NULL,
  `manager_description` text,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `stu_semesters`
--

CREATE TABLE `stu_semesters` (
  `id` int NOT NULL,
  `semester_id` int NOT NULL,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `uni_group`
--

CREATE TABLE `uni_group` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `nationalcode` int DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `image_path`, `birthdate`, `nationalcode`, `phone`, `email`, `password`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(4, 'سجاد', 'پاسبان رضوی', 'storage/user/user-4-profile-pic.jpg', '2000-11-02', 925471429, '09374812890', 's.pr98@yahoo.com', '$2y$10$bn5Hon3JV7L1sKrrfhb0BOAiY5mCfYg78C4VAelhJ/jBTV00t6g86', 1, '2024-06-03 15:14:10', '2024-06-03 15:14:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_access`
--

CREATE TABLE `users_access` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `group_id` int NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `default_role` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`id`, `user_id`, `role_id`, `default_role`, `status`, `create_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(9, 4, 2, 1, 1, '2024-06-30 17:40:31', '2024-06-30 17:40:31', NULL, NULL),
(10, 4, 3, 0, 1, '2024-06-30 19:14:02', '2024-06-30 19:14:02', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_registration_application`
--
ALTER TABLE `company_registration_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intern_recruitment_application`
--
ALTER TABLE `intern_recruitment_application`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cra_id` (`cra_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_param` (`key_param`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_access`
--
ALTER TABLE `roles_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sites_management`
--
ALTER TABLE `sites_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stu_request`
--
ALTER TABLE `stu_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `stu_semester_id` (`stu_semester_id`),
  ADD KEY `teacher` (`teacher`),
  ADD KEY `ira_id` (`ira_id`);

--
-- Indexes for table `stu_semesters`
--
ALTER TABLE `stu_semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `uni_group`
--
ALTER TABLE `uni_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nationalcode` (`nationalcode`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_access`
--
ALTER TABLE `users_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_token`
--
ALTER TABLE `auth_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_registration_application`
--
ALTER TABLE `company_registration_application`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `degree`
--
ALTER TABLE `degree`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `intern_recruitment_application`
--
ALTER TABLE `intern_recruitment_application`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles_access`
--
ALTER TABLE `roles_access`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sites_management`
--
ALTER TABLE `sites_management`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stu_request`
--
ALTER TABLE `stu_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stu_semesters`
--
ALTER TABLE `stu_semesters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `uni_group`
--
ALTER TABLE `uni_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users_access`
--
ALTER TABLE `users_access`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD CONSTRAINT `auth_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `city_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `province` (`id`);

--
-- Constraints for table `company_registration_application`
--
ALTER TABLE `company_registration_application`
  ADD CONSTRAINT `company_registration_application_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `intern_recruitment_application`
--
ALTER TABLE `intern_recruitment_application`
  ADD CONSTRAINT `intern_recruitment_application_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`),
  ADD CONSTRAINT `intern_recruitment_application_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `intern_recruitment_application_ibfk_3` FOREIGN KEY (`cra_id`) REFERENCES `company_registration_application` (`id`),
  ADD CONSTRAINT `intern_recruitment_application_ibfk_4` FOREIGN KEY (`group_id`) REFERENCES `uni_group` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `roles_access`
--
ALTER TABLE `roles_access`
  ADD CONSTRAINT `roles_access_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `roles_access_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Constraints for table `stu_request`
--
ALTER TABLE `stu_request`
  ADD CONSTRAINT `stu_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stu_request_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`),
  ADD CONSTRAINT `stu_request_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `uni_group` (`id`),
  ADD CONSTRAINT `stu_request_ibfk_4` FOREIGN KEY (`stu_semester_id`) REFERENCES `stu_semesters` (`id`),
  ADD CONSTRAINT `stu_request_ibfk_5` FOREIGN KEY (`teacher`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stu_request_ibfk_6` FOREIGN KEY (`ira_id`) REFERENCES `intern_recruitment_application` (`id`);

--
-- Constraints for table `stu_semesters`
--
ALTER TABLE `stu_semesters`
  ADD CONSTRAINT `stu_semesters_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`),
  ADD CONSTRAINT `stu_semesters_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `uni_group` (`id`),
  ADD CONSTRAINT `stu_semesters_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_access`
--
ALTER TABLE `users_access`
  ADD CONSTRAINT `users_access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_access_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_groups_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `uni_group` (`id`);

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `users_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
