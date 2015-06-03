<?php

/* overall_footer.html */
class __TwigTemplate_8fdaa6cea29c5ad349e7e83894d603c2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "\t\t";
        // line 2
        echo "\t</div>

";
        // line 4
        // line 5
        echo "
<div id=\"page-footer\" role=\"contentinfo\">
\t";
        // line 7
        $location = "navbar_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->env->loadTemplate("navbar_footer.html")->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 8
        echo "
\t<div class=\"copyright\">
\t\t";
        // line 10
        // line 11
        echo "\t\t";
        echo (isset($context["CREDIT_LINE"]) ? $context["CREDIT_LINE"] : null);
        echo "
\t\t";
        // line 12
        if ((isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null)) {
            echo "<br />";
            echo (isset($context["TRANSLATION_INFO"]) ? $context["TRANSLATION_INFO"] : null);
        }
        // line 13
        echo "\t\t";
        // line 14
        echo "\t\t";
        if ((isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null)) {
            echo "<br />";
            echo (isset($context["DEBUG_OUTPUT"]) ? $context["DEBUG_OUTPUT"] : null);
        }
        // line 15
        echo "\t\t";
        if ((isset($context["U_ACP"]) ? $context["U_ACP"] : null)) {
            echo "<br /><strong><a href=\"";
            echo (isset($context["U_ACP"]) ? $context["U_ACP"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb')->lang("ACP");
            echo "</a></strong>";
        }
        // line 16
        echo "\t</div>

\t<div id=\"darkenwrapper\" data-ajax-error-title=\"";
        // line 18
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TITLE");
        echo "\" data-ajax-error-text=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT");
        echo "\" data-ajax-error-text-abort=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_ABORT");
        echo "\" data-ajax-error-text-timeout=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_TIMEOUT");
        echo "\" data-ajax-error-text-parsererror=\"";
        echo $this->env->getExtension('phpbb')->lang("AJAX_ERROR_TEXT_PARSERERROR");
        echo "\">
\t\t<div id=\"darken\">&nbsp;</div>
\t</div>
\t<div id=\"loading_indicator\"></div>

\t<div id=\"phpbb_alert\" class=\"phpbb_alert\" data-l-err=\"";
        // line 23
        echo $this->env->getExtension('phpbb')->lang("ERROR");
        echo "\" data-l-timeout-processing-req=\"";
        echo $this->env->getExtension('phpbb')->lang("TIMEOUT_PROCESSING_REQ");
        echo "\">
\t\t<a href=\"#\" class=\"alert_close\"></a>
\t\t<h3 class=\"alert_title\">&nbsp;</h3><p class=\"alert_text\"></p>
\t</div>
\t<div id=\"phpbb_confirm\" class=\"phpbb_alert\">
\t\t<a href=\"#\" class=\"alert_close\"></a>
\t\t<div class=\"alert_text\"></div>
\t</div>
</div>

</div>

<div>
\t<a id=\"bottom\" class=\"anchor\" accesskey=\"z\"></a>
\t";
        // line 37
        if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
            echo (isset($context["RUN_CRON_TASK"]) ? $context["RUN_CRON_TASK"] : null);
        }
        // line 38
        echo "</div>

<script type=\"text/javascript\" src=\"";
        // line 40
        echo (isset($context["T_JQUERY_LINK"]) ? $context["T_JQUERY_LINK"] : null);
        echo "\"></script>
";
        // line 41
        if ((isset($context["S_ALLOW_CDN"]) ? $context["S_ALLOW_CDN"] : null)) {
            echo "<script type=\"text/javascript\">window.jQuery || document.write(unescape('%3Cscript src=\"";
            echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
            echo "/javascript/jquery.min.js?assets_version=";
            echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
            echo "\" type=\"text/javascript\"%3E%3C/script%3E'));</script>";
        }
        // line 42
        echo "<script type=\"text/javascript\" src=\"";
        echo (isset($context["T_ASSETS_PATH"]) ? $context["T_ASSETS_PATH"] : null);
        echo "/javascript/core.js?assets_version=";
        echo (isset($context["T_ASSETS_VERSION"]) ? $context["T_ASSETS_VERSION"] : null);
        echo "\"></script>
";
        // line 43
        $asset_file = "forum_fn.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            $asset->add_assets_version('1');
            $asset_file = $asset->get_url();
            }
        }
        $context['definition']->append('SCRIPTS', '<script type="text/javascript" src="' . $asset_file. '"></script>

');
        // line 44
        $asset_file = "ajax.js";
        $asset = new \phpbb\template\asset($asset_file, $this->getEnvironment()->get_path_helper());
        if (substr($asset_file, 0, 2) !== './' && $asset->is_relative()) {
            $asset_path = $asset->get_path();            $local_file = $this->getEnvironment()->get_phpbb_root_path() . $asset_path;
            if (!file_exists($local_file)) {
                $local_file = $this->getEnvironment()->findTemplate($asset_path);
                $asset->set_path($local_file, true);
            $asset->add_assets_version('1');
            $asset_file = $asset->get_url();
            }
        }
        $context['definition']->append('SCRIPTS', '<script type="text/javascript" src="' . $asset_file. '"></script>

');
        // line 45
        echo "
";
        // line 46
        // line 47
        echo "
";
        // line 48
        if ((isset($context["S_PLUPLOAD"]) ? $context["S_PLUPLOAD"] : null)) {
            $location = "plupload.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->env->loadTemplate("plupload.html")->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 49
        echo $this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "SCRIPTS");
        echo "

";
        // line 51
        // line 52
        echo "
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "overall_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  191 => 49,  174 => 47,  133 => 42,  121 => 40,  113 => 37,  78 => 18,  65 => 15,  59 => 14,  47 => 11,  42 => 8,  21 => 2,  376 => 103,  372 => 101,  366 => 99,  363 => 98,  356 => 93,  354 => 92,  351 => 91,  347 => 89,  336 => 84,  318 => 81,  312 => 80,  309 => 79,  299 => 78,  295 => 76,  280 => 73,  272 => 71,  270 => 70,  247 => 65,  245 => 64,  237 => 60,  228 => 58,  214 => 54,  206 => 51,  204 => 50,  198 => 49,  167 => 46,  162 => 44,  129 => 37,  111 => 36,  98 => 33,  93 => 32,  92 => 31,  89 => 30,  85 => 28,  80 => 26,  67 => 19,  51 => 17,  44 => 12,  41 => 11,  37 => 9,  27 => 3,  160 => 39,  145 => 40,  138 => 39,  125 => 41,  112 => 28,  108 => 35,  82 => 27,  68 => 21,  52 => 12,  46 => 10,  38 => 9,  36 => 8,  30 => 7,  25 => 4,  416 => 111,  406 => 109,  404 => 108,  401 => 107,  400 => 106,  397 => 105,  395 => 104,  389 => 103,  388 => 102,  375 => 101,  360 => 98,  358 => 97,  348 => 90,  345 => 95,  340 => 86,  335 => 90,  332 => 89,  320 => 85,  311 => 84,  304 => 81,  292 => 80,  284 => 79,  279 => 78,  261 => 72,  257 => 70,  217 => 55,  196 => 51,  193 => 51,  192 => 50,  183 => 48,  180 => 47,  169 => 46,  158 => 45,  157 => 44,  142 => 34,  141 => 42,  135 => 38,  134 => 37,  127 => 34,  118 => 29,  110 => 32,  107 => 31,  105 => 26,  102 => 34,  88 => 25,  81 => 24,  74 => 16,  69 => 21,  61 => 19,  50 => 16,  45 => 14,  43 => 13,  40 => 10,  39 => 11,  32 => 7,  26 => 5,  386 => 104,  383 => 103,  373 => 100,  369 => 99,  367 => 96,  362 => 97,  361 => 92,  357 => 90,  344 => 88,  343 => 87,  338 => 85,  330 => 88,  322 => 86,  316 => 78,  308 => 83,  303 => 75,  300 => 74,  296 => 72,  290 => 69,  286 => 68,  282 => 67,  255 => 57,  246 => 66,  242 => 64,  241 => 62,  236 => 50,  233 => 49,  232 => 61,  220 => 58,  212 => 53,  210 => 40,  207 => 39,  199 => 37,  197 => 52,  190 => 34,  186 => 33,  181 => 32,  177 => 48,  171 => 30,  155 => 44,  153 => 42,  150 => 41,  117 => 38,  106 => 14,  95 => 27,  84 => 23,  73 => 11,  62 => 10,  35 => 7,  22 => 2,  301 => 74,  298 => 73,  297 => 77,  294 => 71,  289 => 68,  288 => 75,  277 => 66,  276 => 77,  271 => 63,  268 => 62,  266 => 74,  263 => 68,  258 => 71,  256 => 67,  230 => 55,  229 => 54,  224 => 60,  221 => 59,  219 => 56,  216 => 49,  211 => 46,  209 => 52,  200 => 44,  189 => 43,  188 => 42,  185 => 49,  173 => 46,  170 => 45,  168 => 38,  165 => 45,  164 => 36,  161 => 22,  154 => 31,  149 => 30,  143 => 28,  140 => 43,  132 => 38,  130 => 35,  123 => 23,  116 => 22,  101 => 20,  96 => 25,  94 => 23,  91 => 17,  90 => 16,  87 => 29,  75 => 14,  72 => 13,  71 => 20,  63 => 18,  60 => 9,  58 => 18,  57 => 13,  54 => 16,  48 => 15,  34 => 3,  31 => 6,  19 => 1,);
    }
}
