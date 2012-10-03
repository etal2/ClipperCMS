<?php
/**
 * ClipperCMS installation language file
 *
 * @author davaeron
 * @package ClipperCMS installer
 * @version 1.0
 * @translation     INFORMATOR TEAM www.informator.org  23.05.2009
 * Filename:       /install/lang/bulgarian/bulgarian.inc.php
 * Language:       English
 * Encoding:       utf-8
 */
$_lang["alert_database_test_connection"] = 'Трябва да създадете ваша БД или да проверите избирането на БД!';
$_lang["alert_database_test_connection_failed"] = 'Проверката за избиране на вашата БД е неуспешна!';
$_lang["alert_enter_adminconfirm"] = 'Админстраторската парола не съвпада с паролата за потвърждение!';
$_lang["alert_enter_adminlogin"] = 'Трябва да въведете Потребителско име за Системния Администраторски акаунт!';
$_lang["alert_enter_adminpassword"] = 'Трябва да въведете Парола за Системния Администраторски акаунт!';
$_lang["alert_enter_database_name"] = 'Трябва да въведете валидно име на БД!';
$_lang["alert_enter_host"] = 'Трябва да въведете стойност за хоста на БД!';
$_lang["alert_enter_login"] = 'Трябва да въведете име на потребител за достъп до БД!';
$_lang["alert_server_test_connection"] = 'Трябва да тествате връзката към сървъра!';
$_lang["alert_server_test_connection_failed"] = 'Проверката на връзката към сървъра е неуспешна!';
$_lang["alert_table_prefixes"] = 'Префиксът на таблицата трябва да започва с буква!';
$_lang["all"] = 'Всички';
$_lang["and_try_again"] = ', и опитайте отново. Ако имате нужда от помощ при оправяне на проблема';
$_lang["and_try_again_plural"] = ', и опитайте отново. Ако имате нужда от помощ при оправяне на проблемите';
$_lang["begin"] = 'Начало';
$_lang["btnback_value"] = 'Назад';
$_lang["btnclose_value"] = 'Затваряне';
$_lang["btnnext_value"] = 'Напред';
$_lang["cant_write_config_file"] = 'ClipperCMS не успя да запише конфигурационния файл. Моля, копирайте следното във файла ';
$_lang["cant_write_config_file_note"] = 'След като инсталацията завърши, можете да се логнете в Мениджъра на ClipperCMS, като напишете в браузера си YourSiteName.com/manager/.';
$_lang["checkbox_select_options"] = 'Опции:';
$_lang["checking_if_cache_exist"] = 'Проверка дали съществува директорията <span class=\"mono\">assets/cache</span> : ';
$_lang["checking_if_cache_file2_writable"] = 'Проверка дали може да се пише във файла <span class=\"mono\">assets/cache/sitePublishing.idx.php</span> : ';
$_lang["checking_if_cache_file_writable"] = 'Проверка дали може да се пише във файла <span class=\"mono\">assets/cache/siteCache.idx.php</span> : ';
$_lang["checking_if_cache_writable"] = 'Проверка дали в директорията <span class=\"mono\">assets/cache</span> може да се пише: ';
$_lang["checking_if_config_exist_and_writable"] = 'Проверка дали <span class=\"mono\">manager/includes/config.inc.php</span> съществува и може да се пише в него: ';
$_lang["checking_if_export_exists"] = 'Проверка дали съществува директорията <span class=\"mono\">assets/export</span> : ';
$_lang["checking_if_export_writable"] = 'Проверка дали в директорията <span class=\"mono\">assets/export</span> може да се пише: ';
$_lang["checking_if_images_exist"] = 'Проверка дали съществува директорията <span class=\"mono\">assets/images</span> : ';
$_lang["checking_if_images_writable"] = 'Проверка дали в директорията <span class=\"mono\">assets/images</span> може да се пише: ';
$_lang["checking_mysql_strict_mode"] = 'Проверка дали MySQL е в strict mode: ';
$_lang["checking_mysql_version"] = 'Проверка на версията на MySQL: ';
$_lang["checking_php_version"] = 'Проверка на PHP версията: ';
$_lang["checking_registerglobals"] = 'Проверка дали Register_Globals са изключени: ';
$_lang["checking_registerglobals_note"] = 'Тази конфигурация прави сайта ви много по-уязвим към Cross Site Scripting (XSS) атаки. Трябва да говорите с вашия доставчик за забраняване на тази настройка, което става обикновено по един от трите начина: модифициране на основния php.ini файл, добавяне на правила в .htaccess файла в основната директория (root) на вашата ClipperCMS инсталация, или добавяне на собствен php.ini във всяка директория на вашата инсталация, за да препокрие основния файл. Въпреки, това ще можете да инсталирате ClipperCMS, но се считайте за предупредени.'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'Проверка дали сесиите са правилно конфигурирани: ';
$_lang["checking_table_prefix"] = 'Проверка на префикса на таблицата `';
$_lang["chunks"] = 'Чънкове';
$_lang["config_permissions_note"] = 'За нови Linux/Unix инсталации, моля, създайте празен файл с име <span class=\"mono\">config.inc.php</span> в директорията <span class=\"mono\">manager/includes/</span> и задайте права на файла 0666.';
$_lang["connection_screen_collation"] = 'Колация:';
$_lang["connection_screen_connection_information"] = 'Информация за връзката';
$_lang["connection_screen_connection_method"] = 'Начин на свързване - метод:';
$_lang["connection_screen_database_connection_information"] = 'Информация за БД';
$_lang["connection_screen_database_connection_note"] = 'Моля, въведете името на БД, създадена за ClipperCMS. В случай, че няма такава БД, инсталаторът ще се опита да я създаде. Създаването може да е неуспешно, тъй като зависи от конфигурацията на MySQL или правата за достъп до БД на потребителя.';
$_lang["connection_screen_database_host"] = 'Хостване на БД:';
$_lang["connection_screen_database_login"] = 'Име за връзка към БД:';
$_lang["connection_screen_database_name"] = 'Име на БД:';
$_lang["connection_screen_database_pass"] = 'Парола за БД:';
$_lang["connection_screen_database_test_connection"] = 'Кликнете, за да създадете БД или да тествате връзката към нея';
$_lang["connection_screen_default_admin_email"] = 'Em@il адрес:';
$_lang["connection_screen_default_admin_login"] = 'Потребителско Име:';
$_lang["connection_screen_default_admin_note"] = 'Трябва да въведете данните за главния администраторски акаунт. Попълнете името и паролата си тук, за да не ги забравите. Ще имате нужда от тях за логване след като приключи инсталацията.';
$_lang["connection_screen_default_admin_password"] = 'Парола:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Потвърди паролата:';
$_lang["connection_screen_default_admin_user"] = 'Администратор по подразбиране';
$_lang["connection_screen_server_connection_information"] = 'Връзка към сървъра и информация за логване';
$_lang["connection_screen_server_connection_note"] = 'Моля, въведете името на сървъра, потребителското име и паролата и тогава тествайте връзката.';
$_lang["connection_screen_server_test_connection"] = 'Кликнете, за да тествате връзката към сървъра и достъпните колации.';
$_lang["connection_screen_table_prefix"] = 'Префикс на таблицата:';
$_lang["creating_database_connection"] = 'Създаване на връзка към БД: ';
$_lang["database_alerts"] = 'БД - внимание!';
$_lang["database_connection_failed"] = 'Връзката към БД неуспешна!';
$_lang["database_connection_failed_note"] = 'Моля, проверете данните за връзка с БД и опитайте отново.';
$_lang["database_use_failed"] = 'Базата от Данни не може да бъде избрана!';
$_lang["database_use_failed_note"] = 'Моля, проверете правата за достъп до БД на определения потребител и опитайте отново.';
$_lang["during_execution_of_sql"] = ' по време на изпълнение на SQL условие ';
$_lang["encoding"] = 'utf-8';
$_lang["error"] = 'грешка';
$_lang["errors"] = 'грешки';
$_lang["failed"] = 'Неуспешно!';
$_lang["iagree_box"] = 'Съгласен съм с условията на използване на този лиценз.';
$_lang["install"] = 'Инсталиране';
$_lang["install_overwrite"] = 'Инсталиране/Презаписване - ';
$_lang["install_results"] = 'Резултати от инсталацията';
$_lang["install_update"] = 'Инсталиране/Обновяване - ';
$_lang["installation_error_occured"] = 'Следните грешки възникнаха по време на инсталацията';
$_lang["installation_install_new_copy"] = 'Инсталиране на ново копие на ';
$_lang["installation_install_new_note"] = '. Моля, имайте предвид, че някоя опция може да презапише данните вътре в БД.';
$_lang["installation_mode"] = 'Режим на Инсталация';
$_lang["installation_new_installation"] = 'Нова Инсталация';
$_lang["installation_note"] = '<strong>Забележка:</strong> След логване в Мениджъра, трябва да редактирате и съхраните настройките на Системата Конфигурция преди да преглеждате сайта си, като изберете <strong>Администрация</strong> -> Системна Конфигурация в Мениджъра на ClipperCMS.';
$_lang["installation_successful"] = 'Инсталацията беше успешна!';
$_lang["installation_upgrade_advanced"] = 'Обновяване за напреднали<br /><small>(редактиране на конфигурацията<br /> на БД)</small>';
$_lang["installation_upgrade_advanced_note"] = 'За напреднали администратори на БД или при преместване на сървъри с различна колация на БД. <b>Трябва да знаете пълното име на БД, потребителското име, парола и подробности за връзката/колацията.</b>';
$_lang["installation_upgrade_existing"] = 'Обновяване на инсталация';
$_lang["installation_upgrade_existing_note"] = 'Обновяване на налични файлове и БД.';
$_lang["installed"] = 'Инсталиран';
$_lang["installing_demo_site"] = 'Инсталиране на демо сайт: ';
$_lang["language_code"] = 'bg';
$_lang["loading"] = 'Зареждане...';
$_lang["modules"] = 'Модули';
$_lang["modx_footer1"] = '&copy; 2012 <a href="http://clippercms.com" target="_blank" >ClipperCMS</a> Content Management Framework (CMF) проект. Всички права запазени. ClipperCMS е лицензиран под GNU GPL.';
$_lang["modx_footer2"] = 'ClipperCMS е свободен софтуер.  Бъдете креативни и свикнете да работите с ClipperCMS. Имайте предвид, че ако решите да правите промени и да предоставяте копие от вашия модифициран ClipperCMS, изходния код трябва да е свободен!';
$_lang["modx_install"] = 'ClipperCMS &raquo; Инсталиране';
$_lang["modx_requires_php"] = ', и ClipperCMS върви на PHP 5 или по-висока';
$_lang["mysql_5051"] = ' MySQL версията на сървъра е 5.0.51!';
$_lang["mysql_5051_warning"] = 'Има известни спорни въпроси с MySQL 5.0.51. Препоръчително е да обновите преди да продължите.';
$_lang["mysql_version_is"] = ' MySQL версията ви е: ';
$_lang["none"] = 'Никой';
$_lang["not_found"] = 'не е намерен';
$_lang["ok"] = 'ОК!';
$_lang["optional_items"] = 'Допълнителни опции';
$_lang["optional_items_note"] = 'Моля, изберете допълнителни опции и кликнете на Инсталиране на:';
$_lang["php_security_notice"] = '<legend>Бележки за сигурността</legend><p>Тъй като ClipperCMS ще работи на вашата PHP версия, използването на ClipperCMS на тази версия не се препоръчва. Вашата версия на PHP е уязвима по отношение на дупки в сигурността. Моля,обновете към PHP версия 4.3.8 или по-висока. Препоръчително е да обновите версията си за сигурността на вашия сайт.</p>';
$_lang["please_correct_error"] = '. Моля, коригирайте грешката';
$_lang["please_correct_errors"] = '. Моля, коригирайте грешките';
$_lang["plugins"] = 'Плъгини';
$_lang["preinstall_validation"] = 'Потвърждаване на инсталацията';
$_lang["remove_install_folder_auto"] = 'Изтрий директория install и файловете, които се намират в нея <br />&nbsp;(Тази операция изисква да бъдат гарантирани права за изтриване върху директорията install).';
$_lang["remove_install_folder_manual"] = 'Моля, запомнете да изтриете  директорията &quot;<b>install</b>&quot; преди да се логнете в Мениджъра.';
$_lang["retry"] = 'Опитай отново';
$_lang["running_database_updates"] = 'Обновяване на БД: ';
$_lang["sample_web_site"] = 'Примерен Уеб Сайт';
$_lang["sample_web_site_note"] = 'Моля, имайте предвид, че това ще <b style=\"color:#CC0000\">презапише</b> съществуващите документи и ресурси.';
$_lang["setup_cannot_continue"] = 'За съжаление Инсталаторът не може да продължи в момента поради ';
$_lang["setup_couldnt_install"] = 'Инсталаторът на ClipperCMS не може да инсталира/промени някои таблици вътре в избраната БД.';
$_lang["setup_database"] = 'Инсталаторът ще направи опит да конфигирира БД:<br />';
$_lang["setup_database_create_connection"] = 'Създаване на връзка към БД: ';
$_lang["setup_database_create_connection_failed"] = 'Връзката към БД неуспешна!';
$_lang["setup_database_create_connection_failed_note"] = 'Моля, проверете данните за логване към БД и опитайте отново.';
$_lang["setup_database_creating_tables"] = 'Създаване на таблици в БД: ';
$_lang["setup_database_creation"] = 'Създаване на БД `';
$_lang["setup_database_creation_failed"] = 'Създаването на БД е неуспешно!';
$_lang["setup_database_creation_failed_note"] = ' - Инсталаторът не успя да създаде БД!';
$_lang["setup_database_creation_failed_note2"] = 'Инсталаторът не успя да създаде БД, а също не намери съществуваща БД със същото име. Вероятно, сигурността при вашия хостинг доставчик не позволява външни скриптове да създават БД. Моля, създайте БД според условията на вашия хостинг доставчик и стартирайте Инсталатора отново.';
$_lang["setup_database_selection"] = 'Избиране на БД `';
$_lang["setup_database_selection_failed"] = 'Избирането на БД е неуспешно...';
$_lang["setup_database_selection_failed_note"] = 'БД не съществува. Инсталаторът ще се опита да я създаде.';
$_lang["snippets"] = 'Снипети';
$_lang["some_tables_not_updated"] = 'Някои таблици не бяха обновени. Това може да е вследствие на предишни модификации.';
$_lang["status_checking_database"] = 'Проверка на БД: ';
$_lang["status_connecting"] = ' Свързване с хостинг доставчика: ';
$_lang["status_failed"] = 'Неуспешно!';
$_lang["status_failed_could_not_create_database"] = 'Неуспешно - не може да бъде създадена БД';
$_lang["status_failed_table_prefix_already_in_use"] = 'Неуспешно - префикса на таблицата вече се използва!';
$_lang["status_passed"] = 'Успешно - БД е избрана';
$_lang["status_passed_database_created"] = 'Успешно - БД е създадена';
$_lang["status_passed_server"] = 'Успешно - колациите са достъпни';
$_lang["strict_mode"] = ' MySQL сървъра е в strict mode!';
$_lang["strict_mode_error"] = 'ClipperCMS изисква strict mode да е disabled. Това можете да направите чрез настройките за режима на MySQL , като редактирате файла my.cnf или като се свържете със администратора на сървъра.';
$_lang["summary_setup_check"] = 'Направени бяха редица проверки, за да сме сигурни, че инсталацията може да започне.';
$_lang["table_prefix_already_inuse"] = ' - Префиксът на таблицата вече се използва в тази БД!';
$_lang["table_prefix_already_inuse_note"] = 'Инсталаторът не може да инсталира в избраната БД, тъй като в нея вече съществуват таблици със зададения префикс. Моля, изберете нов префикс за таблицата и стартирайте Инсталатора отново.';
$_lang["table_prefix_not_exist"] = ' - Префиксът на таблицата не съществува в тази БД!';
$_lang["table_prefix_not_exist_note"] = 'Инсталаторът не може да инсталира в избраната БД, тъй като тя не съдържа таблици със зададения префикс, които да бъдат обновявени. Моля, изберете съществуващ префикс на таблица  и стартирайте Инсталатора отново.';
$_lang["templates"] = 'Шаблони';
$_lang["to_log_into_content_manager"] = 'За да се логнете в Мениджъра (manager/index.php) кликнете на бутона `Затваряне` .';
$_lang["toggle"] = 'Включване';
$_lang["unable_install_chunk"] = 'Не успя да инсталира Чънк.  Файл';
$_lang["unable_install_module"] = 'Не успя да инсталира Модул.  Файл';
$_lang["unable_install_plugin"] = 'Не успя да инсталира Плъгин.  Файл';
$_lang["unable_install_snippet"] = 'Не успя да инсталира Снипет.  Файл';
$_lang["unable_install_template"] = 'Не успя да инсталира Шаблон.  Файл';
$_lang["upgrade_note"] = '<strong>Забележка:</strong> Преди да преглеждате сайта си, трябва да се логнете в Мениджъра с администраторския акаунт, след това да прегледате и съхраните настройките на Системната Конфигурация.';
$_lang["upgraded"] = 'Обновен';
$_lang["visit_forum"] = ', посетете <a href="http://clippercms.comforums/" target="_blank">ClipperCMS Форумите</a>.';
$_lang["welcome_message_text"] = 'Тази програма ще ви води през останалата част от инсталацията.';
$_lang["welcome_message_welcome"] = 'Добре дошли в Инсталатора на ClipperCMS.';
$_lang["writing_config_file"] = 'Записване на конфигурационния файл: ';
$_lang["you_running_php"] = ' - PHP версията ви е ';
?>
