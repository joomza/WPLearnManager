<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSLEARNMANAGERcaptcha {

    function getCaptchaForForm() {
        $config_array = JSLEARNMANAGERincluder::getJSModel('configuration')->getConfigByFor('captcha');
        $rand = $this->randomNumber();
        $msgkey = 'captcha';
            JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable($rand,'','jslearnmanager_spamcheckid',$msgkey);
        $jslearnmanager_rot13 = mt_rand(0, 1);
        JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable($jslearnmanager_rot13,'','jslearnmanager_rot13',$msgkey);
        $operator = 2;
        if ($operator == 2) {
            $tcalc = $config_array['owncaptcha_calculationtype'];
        }
        $max_value = 20;
        $negativ = 1;
        $operend_1 = mt_rand($negativ, $max_value);
        $operend_2 = mt_rand($negativ, $max_value);
        $operand = $config_array['owncaptcha_totaloperand'];
        if ($operand == 3) {
            $operend_3 = mt_rand($negativ, $max_value);
        }

        if ($config_array['owncaptcha_calculationtype'] == 2) { // Subtraction
            if ($config_array['owncaptcha_subtractionans'] == 1) {
                $ans = $operend_1 - $operend_2;
                if ($ans < 0) {
                    $one = $operend_2;
                    $operend_2 = $operend_1;
                    $operend_1 = $one;
                }
                if ($operand == 3) {
                    $ans = $operend_1 - $operend_2 - $operend_3;
                    if ($ans < 0) {
                        if ($operend_1 < $operend_2) {
                            $one = $operend_2;
                            $operend_2 = $operend_1;
                            $operend_1 = $one;
                        }
                        if ($operend_1 < $operend_3) {
                            $one = $operend_3;
                            $operend_3 = $operend_1;
                            $operend_1 = $one;
                        }
                    }
                }
            }
        }

        if ($tcalc == 0)
            $tcalc = mt_rand(1, 2);

        if ($tcalc == 1) { // Addition
            if ($jslearnmanager_rot13 == 1) { // ROT13 coding
                if ($operand == 2) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(str_rot13(base64_encode($operend_1 + $operend_2)),'','jslearnmanager_spamcheckresult',$msgkey);
                } elseif ($operand == 3) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(str_rot13(base64_encode($operend_1 + $operend_2 + $operend_3)),'','jslearnmanager_spamcheckresult',$msgkey);
                }
            } else {
                if ($operand == 2) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(base64_encode($operend_1 + $operend_2),'','jslearnmanager_spamcheckresult',$msgkey);
                } elseif ($operand == 3) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(base64_encode($operend_1 + $operend_2 + $operend_3),'','jslearnmanager_spamcheckresult',$msgkey);
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($jslearnmanager_rot13 == 1) {
                if ($operand == 2) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(str_rot13(base64_encode($operend_1 - $operend_2)),'','jslearnmanager_spamcheckresult',$msgkey);
                } elseif ($operand == 3) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(str_rot13(base64_encode($operend_1 - $operend_2 - $operend_3)),'','jslearnmanager_spamcheckresult',$msgkey);
                }
            } else {
                if ($operand == 2) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(base64_encode($operend_1 - $operend_2),'','jslearnmanager_spamcheckresult',$msgkey);
                } elseif ($operand == 3) {
                    JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->addSessionNotificationDataToTable(base64_encode($operend_1 - $operend_2 - $operend_3),'','jslearnmanager_spamcheckresult',$msgkey);
                }
            }
        }
        $add_string = "";
        $add_string .= '<div><label for="' . $rand . '">';

        if ($tcalc == 1) {
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'learn-manager') . ' ' . $operend_2 . ' ' . __('Equals', 'jslearn-manager') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'learn-manager') . ' ' . $operend_2 . ' ' . __('Plus', 'jslearn-manager') . ' ' . $operend_3 . ' ' . __('Equals', 'jslearn-manager') . ' ';
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'learn-manager') . ' ' . $operend_2 . ' ' . __('Equals', 'jslearn-manager') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'learn-manager') . ' ' . $operend_2 . ' ' . __('Minus', 'jslearn-manager') . ' ' . $operend_3 . ' ' . __('Equals', 'jslearn-manager') . ' ';
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . $rand . '" id="' . $rand . '" size="3" class="inputbox ' . $rand . '" value="" data-validation="required" />';
        $add_string .= '</div>';

        return $add_string;
    }

    function randomNumber() {
        $pw = '';

        // first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[mt_rand(0, 25)];

        // other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);

        $pw_length = mt_rand(4, 12);

        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[mt_rand(0, 35)];
        }
        return $pw;
    }

    private function performChecks() {
        $jslearnmanager_rot13 = JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->getNotificationDatabySessionId('jslearnmanager_rot13','captcha',true);
        if ($jslearnmanager_rot13 == 1) {
            $spamcheckresult = base64_decode(str_rot13(JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->getNotificationDatabySessionId('jslearnmanager_spamcheckresult','captcha',true)));
        } else {
            $spamcheckresult = base64_decode(JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->getNotificationDatabySessionId('jslearnmanager_spamcheckresult','captcha',true));
        }
        $spamcheck = JSLEARNMANAGERincluder::getObjectClass('wplmsnotification')->getNotificationDatabySessionId('jslearnmanager_spamcheckid','captcha',true);
        $spamcheck = JSLEARNMANAGERrequest::getVar($spamcheck, '', 'post');
        if ($spamcheckresult != $spamcheck) {
            return false; // Failed
        }
        return true;
    }

    function checkCaptchaUserForm() {
        if (!$this->performChecks())
            $return = 2;
        else
            $return = 1;
        return $return;
    }

}

?>
