<?php
require_once('backend/sessions.php');
require_once('backend/admin.php');
Sessions::init_session();
if (!Admin::verify() || ((!isset($_POST['edit']) || !isset($_POST['id']) || !isset($_POST['prevpage'])) && (!isset($_POST['add'])))) {
    header('Location: fallback.php');
}
if (Admin::verify()) {
    require_once 'backend/edit_news.php';
    Edit_news::edit();
}
$edit=true;
if (isset($_POST['add'])) {
    $edit=false;
}

require_once('backend/print_content.php');
require_once('backend/utilities.php');
require_once('backend/print_news.php');
require_once('backend/get_products.php');

$DOM = file_get_contents('../html/template.html');

$DOM = str_replace('<title_page_to_insert/>', 'Modifica Prodotto', $DOM);

$DOM = str_replace('<meta_title_to_insert/>', '<meta name="title" content="Modifica Prodotti - Pasticceria Padovana"/>', $DOM);
$DOM = str_replace('<meta_description_to_insert/>', '<meta name="description" content="La Pasticceria Padovana, modifica dei prodotti" />', $DOM);
$DOM = str_replace('<meta_keyword_to_insert/>', '<meta name="keywords" content="Pasticceria,Veneto,Padova,Padovana,Prodotti" />', $DOM);

$DOM = str_replace('<no_index_to_insert/>', '<meta name="robots" content="noindex"/>', $DOM);
$DOM = str_replace('<logo_to_insert/>', Print_content::logo(Utilities::get_page_name()), $DOM);
$DOM = str_replace('<title_h1_to_insert/>', 'Modifica Prodotto', $DOM);

if (isset($_POST['type'])) {
    $prevpage = ucfirst($_POST['type']);
    if ($edit) {
        $DOM = str_replace('<breadcrumb_path_to_insert/>', '<strong>'.$prevpage.' / Modifica prodotto (Amministratore)</strong>', $DOM);
    } else {
        $DOM = str_replace('<breadcrumb_path_to_insert/>', '<strong>'.$prevpage.' / Aggiungi prodotto (Amministratore)</strong>', $DOM);
    }
} else {
    if ($edit) {
        $DOM = str_replace('<breadcrumb_path_to_insert/>', '<strong>Modifica Prodotto (Amministratore)</strong>', $DOM);
    } else {
        $DOM = str_replace('<breadcrumb_path_to_insert/>', '<strong>Aggiungi Prodotto (Amministratore)</strong>', $DOM);
    }
}
  

$DOM = str_replace('<menu_to_insert/>', Print_content::menu('modifica_prodotto.php'), $DOM);

require_once('backend/edit_news.php');
Edit_news::edit();

$news = new Print_news();
$DOM = str_replace('<news_title_to_replace/>', $news->title(), $DOM);
$DOM = str_replace('<news_content_to_replace/>', $news->content(), $DOM);
unset($news);

$DOM = str_replace('<edit_news_admin_to_replace/>', Print_news::admin_zone(), $DOM);

$DOM = str_replace('<timetable_to_insert/>', file_get_contents('../html/components/timetable.html'), $DOM);

if ($edit) {
    $product = (new Get_products())->search_by_code($_POST['id']);
}

$content = file_get_contents('../html/components/edit.html');
$content = str_replace('<title_h2_to_insert/>', 'Da questa pagina puoi aggiungere/modificare un prodotto', $content);
$content = str_replace('<prev_page_to_insert/>', '<form enctype="multipart/form-data" class="general_form" id="edit_form" method="post" action="'.htmlentities($_POST['prevpage']).'">', $content);
if ($edit) {
    $input="Modifica ".$product['Nome'];
} else {
    $input="Aggiungi Prodotto";
}
$content = str_replace('<legend_to_insert/>', $input, $content);
if ($edit) {
    $input=Input_security_check::tag_check($product['Nome']);
} else {
    $input="";
}
$content = str_replace('<title_to_insert/>', '<input class="general_input" type="text" maxlength="40" name="title" id="title" value="'.$input.'"/>', $content);
if ($edit) {
    $input=Input_security_check::tag_check($product['Descrizione']);
}

$content = str_replace('<content_to_insert/>', $input, $content);
$content = str_replace('<type_to_insert/>', '<input type="hidden" name="type" value="'.substr($_POST['type'], 0, -1).'a'.'"/>', $content);
$path='';
if ($edit) {
    $path=$product['Immagine'];
}
$content = str_replace('<file_to_insert/>', '<div><label for="image">Cambia immagine: </label>
                                            <input id="image" name="image" type="file" xml:lang="en" aria-label="Scegli file"  onclick="input_image(this)"/>
                                            <input type="hidden" name="oldimage" value="'.$path.'"/>
                                            <img id="preview" src="'.$path.'" alt="immagine da impostare o sostituire"/></div>', $content);
$content = str_replace('<submit_to_insert/>', '<input id="edit_form_submit" class="general_button" type="submit" value="Modifica" name="writeEdits" aria-label="Modifica"/>', $content);
if ($edit) {
    $content = str_replace('<id_to_insert/>', '<input type="hidden" value="'.$_POST['id'].'" name="id"/>', $content);
} else {
    $content = str_replace('<id_to_insert/>', '', $content);
}
$DOM = str_replace('<page_to_insert/>', $content, $DOM);
$DOM = str_replace('<footer_to_replace/>', '<div id="footer" class="container col-sm-1">', $DOM);
$DOM = str_replace('<login_admin_to_insert/>', Print_content::admin_form(), $DOM);

echo($DOM);
?>