<?php
/**
*
* viewtopic.php [Argentinian Spanish]
*
* @package language
* @copyright (c) 2007 phpBB Group. Modified by nextgen for phpbbargentina.com in 2012 
* @author 2007-11-26 - Traducido por Huan Manwe junto con phpbb-es.com (http://www.phpbb-es.com) basado en la version argentina hecha por larveando.com.ar ).
* @author - ImagePack made nextgen (Styles Team Leader of http://www.phpbbargentina.com)
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
exit;
}

if (empty($lang) || !is_array($lang))
{
$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//

$lang = array_merge($lang, array(
'ATTACHMENT'						=> 'Adjunto',
'ATTACHMENT_FUNCTIONALITY_DISABLED'	=> 'Los adjuntos han sido deshabilitados',

'BOOKMARK_ADDED'					=> 'Tema añadido con éxito a Favoritos.',
'BOOKMARK_ERR'						=> 'Añadido de tema a Favoritos fallido. Por favor, inténtalo de nuevo.',
'BOOKMARK_REMOVED'					=> 'Eliminado con éxito el tema de Favoritos.',
'BOOKMARK_TOPIC'					=> 'Añadir tema a Favoritos',
'BOOKMARK_TOPIC_REMOVE'				=> 'Eliminar de Favoritos',
'BUMPED_BY'							=> 'Última reactivación por %1$s en %2$s',
'BUMP_TOPIC'						=> 'Reactivar tema',

'CODE'								=> 'Código',
'COLLAPSE_QR'			=> 'Ocultar Respuesta Rápida',

'DELETE_TOPIC'				 		=> 'Borrar tema',
'DOWNLOAD_NOTICE'					=> 'No tenés los permisos requeridos para ver los archivos adjuntos a este mensaje.',

'EDITED_TIMES_TOTAL'				=> 'Última edición por %1$s el %2$s, editado %3$d veces en total',
'EDITED_TIME_TOTAL'					=> 'Última edición por %1$s el %2$s, editado %3$d vez en total',
'EMAIL_TOPIC'						=> 'Email a un amigo',
'ERROR_NO_ATTACHMENT'				=> 'El adjunto seleccionado ya no existe',

'FILE_NOT_FOUND_404'				=> 'El archivo <strong>%s</strong> no existe.',
'FORK_TOPIC'						=> 'Copiar tema',
'FULL_EDITOR'			=> 'Editor completo',

'LINKAGE_FORBIDDEN'					=> 'No estás autorizado a ver, descargar o enlazar de/a este Sitio.',
'LOGIN_NOTIFY_TOPIC'				=> 'Has sido notificado sobre este tema, por favor identifícate para verlo.',
'LOGIN_VIEWTOPIC'					=> 'La Administración del Sitio requiere que estés registrado e identificado para ver este tema.',

'MAKE_ANNOUNCE'						=> 'Cambiar a "Anuncio"',
'MAKE_GLOBAL'						=> 'Cambiar a "Global"',
'MAKE_NORMAL'						=> 'Cambiar a "Tema"',
'MAKE_STICKY'						=> 'Cambiar a "Fijo"',
'MAX_OPTIONS_SELECT'				=> 'Podes seleccionar hasta <strong>%d</strong> opciones',
'MAX_OPTION_SELECT'					=> 'Podes seleccionar <strong>1</strong> opción',
'MISSING_INLINE_ATTACHMENT'			=> 'El adjunto <strong>%s</strong> ya no está disponible',
'MOVE_TOPIC'						=> 'Mover tema',

'NO_ATTACHMENT_SELECTED'			=> 'No has seleccionado un adjunto para descargar o ver.',
'NO_NEWER_TOPICS'					=> 'No hay temas nuevos en este foro',
'NO_OLDER_TOPICS'					=> 'No hay temas viejos en este foro',
'NO_UNREAD_POSTS'					=> 'No hay nuevos mensajes sin leer en este tema.',
'NO_VOTE_OPTION'					=> 'Debes especificar una opción cuando votas.',
'NO_VOTES'							=> 'No hay votos',

'POLL_ENDED_AT'						=> 'La encuesta terminó el %s',
'POLL_RUN_TILL'						=> 'La encuesta continúa hasta el %s',
'POLL_VOTED_OPTION'					=> 'Votaste por esta opción',
'PRINT_TOPIC'						=> 'Imprimir vista',

'QUICK_MOD'							=> 'Herramientas Quick-mod',
'QUICKREPLY'			=> 'Respuesta Rápida',
'QUOTE'								=> 'Citar',

'REPLY_TO_TOPIC'					=> 'Responder al tema',
'RETURN_POST'						=> '%sVolver al mensaje%s',
'SHOW_QR'				=> 'Respuesta Rápida',

'SUBMIT_VOTE'						=> 'Enviar voto',

'TOTAL_VOTES'						=> 'Votos totales',

'UNLOCK_TOPIC'						=> 'Desbloquear tema',

'VIEW_INFO'							=> 'Detalles',
'VIEW_NEXT_TOPIC'					=> 'Siguiente tema',
'VIEW_PREVIOUS_TOPIC'				=> 'Tema previo',
'VIEW_RESULTS'						=> 'Ver resultados',
'VIEW_TOPIC_POST'					=> '1 mensaje',
'VIEW_TOPIC_POSTS'					=> '%d mensajes',
'VIEW_UNREAD_POST'					=> 'Primer mensaje sin leer',
'VISIT_WEBSITE'						=> 'WWW',
'VOTE_SUBMITTED'					=> 'Tu voto ha sido enviado',
'VOTE_CONVERTED'					=> 'El cambio de voto no está soportado en encuestas convertidas.',

));

?>