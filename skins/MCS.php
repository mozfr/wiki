<?php
/**
 * Modern skin, derived from monobook template.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinMCS extends SkinTemplate {
	var $skinname = 'mcs', $stylename = 'mcs',
		$template = 'MCSTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		global $wgStylePath;
		$out->addStyle( 'mcs/css/layout.css', 'screen' );
		$out->addStyle( 'mcs/custom.css', 'screen' );
		$out->addStyle( 'mcs/mediawiki.css', 'screen' );
		$out->addStyle( 'mcs/css/rtl.css', 'screen', '', 'rtl' );
		$p = htmlspecialchars($wgStylePath);
		$out->addHeadItem('McsIeStyle', '<!--[if lt IE 8]>
  <meta http-equiv="imagetoolbar" content="no" />
  <link rel="stylesheet" href="'.$p.'/mcs/css/ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lt IE 7]>
  <link rel="stylesheet" href="'.$p.'/mcs/css/ie6.css" type="text/css" media="screen" />
  <script type="text/javascript" src="'.$p.'/mcs/js/dd_belatedpng_0.0.8a-min.js"></script>
  <script type="text/javascript"> 
    DD_belatedPNG.fix(\'*\');
  </script> 
<![endif]-->
');
	}
}
/**
 * @todo document
 * @ingroup Skins
 */
class MCSTemplate extends BaseTemplate {
	var $skin;
	var $tocPos = 'right'; # left | content | content-left | content-right | right
	var $tocStyle = 'expand'; # expand | normal
	/**
	 * Template filter callback for Modern skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		$this->skin = $this->data['skin'];
		$mix = $this->extractTOC($this->data['bodytext']);
		$body = $mix[0];
		$toc = $mix[1];

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?>
  <ul id="nav-access">
    <li><a href="#content">Skip to content<!-- TODO L10N --></a></li>
<?php if($this->data['showjumplinks']) { ?>
		<li><a href="#mw_portlets"><?php $this->msg('jumptonavigation') ?></a></li>
		<li><a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></li>
<?php } ?>
  </ul>
  <div id="header-bar" class="header">
    <div class="doc">
      <div class="logo">
        <img src="<?php $this->text('stylepath' ) ?>/mcs/img/logo/dino.png" class="dino" alt="" />
        <img src="<?php $this->text('stylepath' ) ?>/mcs/img/logo/mcs-logo-dark.png" class="mcs-logo-dark" alt="" />
        <img src="<?php $this->text('stylepath' ) ?>/mcs/img/logo/mozilla.png" class="mozilla" alt="mozilla" />
        <span>community website</span>
      </div>
      <ul class="nav" <?php $this->html('userlangattributes') ?>>
<?php $this->searchBox(); ?>
<?php		foreach($this->getPersonalTools() as $key => $item) { ?>
				<?php echo $this->makeListItem($key, $item); ?>

<?php		} ?>
      </ul>
    </div>
  </div>
	<!-- heading -->
  <div id="header" class="header">
    <div class="doc">
      <a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>">
        <img src="<?php $this->text('stylepath' ) ?>/mcs/img/mctlogo.png" id="logo" alt="" />
        <span id="title">title</span>
      </a>
      <span id="sub-title">Lorem ipsum dolor erat officina</span>
      <ul class="nav">
        <li class="current"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>">Strona główna</a></li>
        <li class=""><a href="http://mozilla.org" title="The page describe basic info about authors">O nas</a></li>
        <li class=""><a href="http://wp.pl" title="">Nasze projekty</a></li>
        <li class=""><a href="http://wp.pl" title="">Promocja</a></li>
        <li class=""><a href="http://wp.pl" title="">Współpraca</a></li>
        <li class=""><a href="http://wp.pl" title="">Dla prasy</a></li>
      </ul>
    </div>
  </div>
  <div id="middle" class="doc">
    <div class="row">
	<?php		$ca_strong = '';
				$ca_normal = '';
				$ca_weak = '';
				foreach($this->data['content_actions'] as $key => $tab) {
					$linkAttribs = array( 'href' => $tab['href'] );

				 	if( isset( $tab["tooltiponly"] ) && $tab["tooltiponly"] ) {
						$title = Linker::titleAttrib( "ca-$key" );
						if ( $title !== false ) {
							$linkAttribs['title'] = $title;
						}
				 	} else {
						$linkAttribs += Linker::tooltipAndAccesskeyAttribs( "ca-$key" );
				 	}
				 	$linkHtml = Html::element( 'a', $linkAttribs, $tab['text'] );

				 	/* Surround with a <li> */
				 	$liAttribs = array( 'id' => Sanitizer::escapeId( "ca-$key" ) );
					if( $tab['class'] ) {
						$liAttribs['class'] = $tab['class'];
					}
				 	$ca = '
				' . Html::rawElement( 'li', $liAttribs, $linkHtml );
				 	if (in_array($key, array('edit', 'addsection')))
				 		$ca_strong .= $ca;
				 	elseif (in_array($key, array('nstab-main', 'edit', 'talk', 'addsection', 'history')))
				 		$ca_normal .= $ca;
				 	else
				 		$ca_weak .= $ca;
				} ?>
<?php
if ($toc and $this->tocPos=='left') {
?>
			<ul class="aside" id="left-menu">
				<li class="box toc border<?php echo ' '.$this->tocStyle?>">
					<?php $this->hackTOC($toc) ?>
				</li>
			</ul>
<?php
}
?>
			<ul class="aside" id="right-menu">
<?php
if ($toc and $this->tocPos=='right') {
?>
				<li class="box toc border<?php echo ' '.$this->tocStyle?>">
					<?php $this->hackTOC($toc) ?>
				</li>
<?php
}
?>
				<li id="p-views" class="box border" <?php $this->html('userlangattributes') ?>>
					<h3><?php $this->msg('views') ?></h3>
					<ul>
						<?php echo $ca_normal . $ca_weak; ?>
					</ul>
				</li>
				<!-- portlets -->
				<?php 
					$sidebar = $this->data['sidebar'];		
					if ( !isset( $sidebar['SEARCH'] ) ) $sidebar['SEARCH'] = true;
					if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
					if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;
			
					foreach ($sidebar as $boxName => $content) {
						if ( $content === false )
							continue;

						if ( $boxName == 'SEARCH' ) {
						} elseif ( $boxName == 'TOOLBOX' ) {
							$this->toolbox();
						} elseif ( $boxName == 'LANGUAGES' ) {
							$this->languageBox();
						} else {
							$this->customBox( $boxName, $content );
						}
					}
				?>
			</ul>
			<div id="content" class="section">
				<?php if ($ca_strong) { ?>
					<ul class="mcs_edit_button metabox linklist right">
						<?php echo $ca_strong; ?>
					</ul>
				<?php } ?>
				<div class="article">
					<h1><?php $this->html('title') ?></h1>
<?php if ($this->data['newtalk'] or $this->data['sitenotice']) { ?>
					<div class='mw-topboxes'>
						<?php if($this->data['newtalk'] ) {
							?><div class="notice"><?php $this->html('newtalk')  ?></div>
						<?php } ?>
						<?php if($this->data['sitenotice']) {
							?><div class="notice" id="siteNotice"><?php $this->html('sitenotice') ?></div>
						<?php } ?>
					</div>
<?php } ?>
<?php if ($this->data['subtitle']) { ?>
					<div id="contentSub" class="subheadline" <?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
<?php } ?>
			
					<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
<?php
if ($toc and strpos($this->tocPos,'content')!==false) {
	$class = strpos($this->tocPos,'left')!==false?'left':(strpos($this->tocPos,'right')!==false?'right':'');
?>
				<div class="box toc border <?php echo $class.' '.$this->tocStyle?>">
					<?php $this->hackTOC($toc) ?>
				</div>
<?php
}
?>
					<?php echo $body ?>
					<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
					<?php $this->html ('dataAfterContent') ?>
				</div>
			</div>
		</div>
	</div>
  <div id="footer" class="footer">
    <div class="doc">
<?php
		$validFooterIcons = $this->getFooterIcons( "icononly" );
		$validFooterLinks = $this->getFooterLinks( "flat" ); // Additional footer links

		if ( count( $validFooterLinks ) > 0 ) {
?>
			<ul class="list nav">
<?php
			foreach( $validFooterLinks as $aLink ) {
?>				<li id="<?php echo $aLink?>"><?php $this->html($aLink) ?></li>
<?php 		}
?>
			</ul>
<?php	}
?>
			<span>Copyright &copy; 2005&#150;2008 Mozilla Atlantis. No rights reserved.</span>
<?php
		// Generate additional footer icons
		// Unset copyright.copyright since we don't need the icon and already output a copyright from footerlinks
		unset($validFooterIcons["copyright"]["copyright"]);
		if ( count($validFooterIcons["copyright"]) <= 0 ) {
			unset($validFooterIcons["copyright"]);
		}
		foreach ( $validFooterIcons as $blockName => $footerIcons ) { ?>
			<div id="mw_<?php echo htmlspecialchars($blockName); ?>">
<?php
			foreach ( $footerIcons as $icon ) { ?>
				<?php echo $this->skin->makeFooterIcon( $icon, 'withoutImage' ); ?>

<?php
			} ?>
			</div>
<?php
		}
?>
    </div>
  </div>
<?php
		$this->printTrail();
?>
</body></html>
<?php
	wfRestoreWarnings();
	} // end of execute() method

	function getMsg( $str ) {
		return htmlspecialchars( $this->translator->translate( $str ) );
	}

	// From MonoBook.php
	/*************************************************************************************************/
	function searchBox() {
		global $wgUseTwoButtonsSearchForm;
?>
	<li id="hb-search-box">
		<form action="<?php $this->text('wgScript') ?>" method="get" id="search">
			<div>
				<input type='hidden' name="title" value="<?php $this->text('searchtitle') ?>"/>
				<?php echo $this->makeSearchInput(array( "id" => "hb-searchinput", "placeholder" => $this->getMsg('search') . "…" )); ?>
				<?php echo $this->makeSearchButton("go", array( "class" => "hb-searchbutton" ));?>
<?php if ($wgUseTwoButtonsSearchForm) { ?>
				<?php echo $this->makeSearchButton("fulltext", array( "class" => "hb-searchbutton" )); ?>
<?php } else { ?>
				<a href="<?php $this->text('searchaction') ?>" rel="search"><?php $this->msg('powersearch-legend') ?></a>
<?php } ?>
			</div>

		</form>
	</li>
<?php
	}

	/*************************************************************************************************/
	function toolbox() {
?>
	<!-- toolbox -->
	<li class="box border portlet" id="p-tb">
		<h3><?php $this->msg('toolbox') ?></h3>
		<ul>
<?php
		foreach ( $this->getToolbox() as $key => $tbitem ) { ?>
				<?php echo $this->makeListItem($key, $tbitem); ?>
<?php
	}
	wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
?>	</ul>
	</li><!-- portlet -->
<?php
	}

	/*************************************************************************************************/
	function languageBox() {
?>
	<!-- languages -->
<?php
		if( $this->data['language_urls'] ) { ?>
	<li id="p-lang" class="box color portlet">
		<h3<?php $this->html('userlangattributes') ?>><?php $this->msg('otherlanguages') ?></h3>
		<ul>
<?php		foreach($this->data['language_urls'] as $key => $langlink) { ?>
				<?php echo $this->makeListItem($key, $langlink); ?>
<?php		} ?>
		</ul>
	</li><!-- portlet -->
<?php
		}
	}

	/*************************************************************************************************/
	function customBox( $bar, $cont ) {
?>
		<li class='box border generated-sidebar portlet' id='p-<?php echo Sanitizer::escapeId($bar) ?>'<?php echo $this->skin->tooltip('p-'.$bar) ?>>
		<h3><?php $msg = wfMessage( $bar ); echo htmlspecialchars( $msg->exists() ? $msg->text() : $bar ); ?></h3>
<?php   if ( is_array( $cont ) ) { ?>
		<ul>
<?php 			foreach($cont as $key => $val) { ?>
				<?php echo $this->makeListItem($key, $val); ?>
<?php			} ?>
		</ul>
<?php   } else {
		# allow raw HTML block to be defined by extensions
		print $cont;
	}
?>
	</li><!-- portlet -->
<?php
	}
	
	function extractTOC(&$body) {
		$toc = '';
		$toc_pattern = '/<table id="toc".*?<\/table>/sim';
		$elems = preg_split($toc_pattern, $body,-1, PREG_SPLIT_OFFSET_CAPTURE);
		if (count($elems)<2)
			return Array($body, $toc);
		
		$toc = substr($body,strlen($elems[0][0]), $elems[1][1]-strlen($elems[0][0]));
		$body = substr($body,0, strlen($elems[0][0])) . substr($body, $elems[1][1]);
		return Array($body, $toc);
	}
	
	function hackTOC($toc) {
		$toc = preg_replace('/<table id="toc".*?<h2>/sim', '<h3 id="toc">', $toc);
		$toc = preg_replace('/<\/h2>\s*<\/div>\s*<ul>/sim', '</h3><ul class="nav">', $toc);
		$toc = preg_replace('/<\/td>.*<\/table>/sim', '', $toc);
		echo $toc;
	}

} // end of class
?>
