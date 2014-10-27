<?php
/**
 * Moztn-TN - Branch of the Mozilla Tunisia MediaWiki skin with some
 * improvements.
 *
 * Loosely based on the Moztn style by Med Firas.
 *
 * Modified 2013/11/28, MedFiras <hajjejfiras@gmail.com> <medfiras@mozilla-tunisia.org>
 *
 *
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinMozTN extends SkinTemplate {
	var $skinname = 'moztn', $stylename = 'moztn',
		$template = 'MozTNTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );

		// Append to the default screen common & print styles...
		$out->addStyle( 'moztn/main.css', 'screen' );
	}

	// This line fixes a later bug in which $skin->tooltipAndAccesskey no longer
	// exist and is now Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs($value)).
	function tooltipAndAccesskey($value) {
        return Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs($value));
    }
}

/**
 * @todo document
 * @ingroup Skins
 */
class MozTNTemplate extends BaseTemplate {
	var $skin;

	/**
	 * Template filter callback for Moztn skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgRequest, $wgSitename,
            $moztnShowSitename, $moztnSitenameIndent,
            $moztnLogoWidth, $moztnLogoHeight;
		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );

        // Set Moztn specific variables.
        $moztnShowSitename = isset($moztnShowSitename) ? $moztnShowSitename : true;
        $moztnSitenameIndent = isset($moztnSitenameIndent) ? $moztnSitenameIndent : '2em';
        $moztnLogoWidth = isset($moztnLogoWidth) ? $moztnLogoWidth : 'auto';
        $moztnLogoHeight = isset($moztnLogoHeight) ? $moztnLogoHeight : 'auto';
        $this->set('sitenameindent', $moztnSitenameIndent);
        $this->set('logowidth', $moztnLogoWidth);
        $this->set('logoheight', $moztnLogoHeight);

        // Retrieve site name
        $this->set('sitename', $wgSitename);

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

        $this->html( 'headelement' );

?>
<!--<link rel="stylesheet" href="//www.mozilla.org/tabzilla/media/css/tabzilla.css">
<a href="http://www.mozilla.org/" id="tabzilla">mozilla</a>-->
<div id="internal"></div> <!-- moztn-mw -->
<div id="container"> <!-- moztn-mw / default: globalWrapper -->

    <!-- <div id="mozilla-org"><a href="mozilla-tunisia.org">Mozilla Tunisia Skin</a></div> -->
    <?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

	<div id="header" class="noprint">
		<a name="top" id="contentTop"></a>
		<div id="logo">
		<h1>
			<a style="width: <?php $this->text('logowidth'); ?>; height: <?php $this->text('logoheight'); ?>;" href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>"<?php echo $skin->tooltipAndAccesskey('p-logo') ?>>
				<img id="img-logo" title="Mozilla Tunisia Wiki" alt="Mozilla Developer Network" src="<?php echo $this->text('scriptpath').'/skins/moztn/Logo.png';?>">			
			</a>
		</h1>
		<h1>
			<a style="width: <?php $this->text('logowidth'); ?>; height: <?php $this->text('logoheight'); ?>;" href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>"<?php echo $skin->tooltipAndAccesskey('p-logo') ?>>
			<span id="text-logo"><?php if ($moztnShowSitename) { $this->text('sitename'); } else { print "&nbsp;"; } ?></span>
		</a></h1>
		</div>
		<ul> <!-- Start of content action buttons -->
            <?php foreach($this->data['content_actions'] as $key => $tab) {
		        echo '
	         <li id="' . Sanitizer::escapeId( "ca-$key" ) . '"';
		        if( $tab['class'] ) {
			        echo ' class="'.htmlspecialchars($tab['class']).'"';
		        }
		        echo'><a href="'.htmlspecialchars($tab['href']).'"';
		        # We don't want to give the watch tab an accesskey if the
		        # page is being edited, because that conflicts with the
		        # accesskey on the watch checkbox.  We also don't want to
		        # give the edit tab an accesskey, because that's fairly su-
		        # perfluous and conflicts with an accesskey (Ctrl-E) often
		        # used for editing in Safari.
	         	if( in_array( $action, array( 'edit', 'submit' ) )
	         	&& in_array( $key, array( 'edit', 'watch', 'unwatch' ))) {
	         		echo $skin->tooltip( "ca-$key" );
	         	} else {
	         		echo $skin->tooltipAndAccesskey( "ca-$key" );
	         	}
	         	echo '>'.htmlspecialchars($tab['text']).'</a></li>';
            } ?>
		</ul>

		<form action="<?php $this->text('searchaction') ?>" id="searchform">
			<div>
			<!--<label for="searchInput"><?php $this->msg('search') ?></label>-->
			<input id="searchInput" placeholder="<?php $this->msg('search') ?>" name="search" type="text" <?php echo $this->skin->tooltipAndAccesskey('search'); if( isset( $this->data['search'] ) ) { ?> value="<?php $this->text('search') ?>"<?php } ?> />
			<!--<input type='submit' name="go" class="searchButton" id="searchGoButton"	value="<?php $this->msg('searcharticle') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-go' ); ?> /> -->
			<!--<input type='submit' name="fulltext" class="searchButton" id="mw-searchButton" value="<?php $this->msg('searchbutton') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?> />-->
			</div>
		</form>

	</div> <!-- End header div -->

	<div id="mBody">

	    <!-- <script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script> -->

	    <!-- NAVIGATION -->
		<div id="side" class="noprint"> <!-- moztn-mw / monobook: column-one -->
			<ul id="nav">
                <?php
                // Display Personal Tools block.
                $this->personalTools();

                // Display other Navigation blocks.
                $sidebar = $this->data['sidebar'];

                if ( !isset( $sidebar['SEARCH'] ) ) $sidebar['SEARCH'] = true;
                if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
                if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;

                foreach ($sidebar as $boxName => $cont) {
                    if ( $boxName == 'SEARCH' ) {
                        // The searchbox is disabled, because we already have one in the header.
                        // Uncomment the line below to enable it again.
                        //$this->searchBox();
                    }
                    elseif ( $boxName == 'TOOLBOX' ) {
                        $this->toolbox();
                    }
                    elseif ( $boxName == 'LANGUAGES' ) {
                        $this->languageBox();
                    }
                    else {
                        $this->customBox( $boxName, $cont );
                    }
                }
                ?>
			</ul>
		</div><!-- end of SIDE div -->

        <!-- MAIN CONTENT -->
	    <div id="mainContent"> <!-- moztn-mw / monobook: column-content -->
            <!-- <a name="top" id="top"></a> -->
            <!-- sitenotice was here -->
           
            <?php /* foreach($this->data['content_actions'] as $key => $tab) {
		        
		        echo'<a href="'.htmlspecialchars($tab['href']).'"';
		        
	         	if( in_array( $action, array( 'edit', 'submit' ) )
	         	&& in_array( $key, array( 'edit', 'watch', 'unwatch' ))) {
	         		echo $skin->tooltip( "ca-$key" );
	         	} else {
	         		echo $skin->tooltipAndAccesskey( "ca-$key" );
	         	}
	         	echo '>';if($tab['text']=='Modifier' || $tab['text']=='Supprimer' || $tab['text']=='Historique') echo htmlspecialchars($tab['text']).'</a>';
            } 
            echo '<br>';
            //foreach($this->data['content_actions'] as $key => $tab) {
            echo $this->data['content_actions']['edit'];
        //}
            */?>
		
            <h1><?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?></h1>
            <h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
            <div id="contentSub"><?php $this->html('subtitle') ?></div>

            <?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
            <?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
            <?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
            <!-- start content -->
            <?php $this->html('bodycontent') ?>
            <?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
            <!-- end content -->
            <?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
            <div class="visualClear"></div>
	    </div> <!-- End mainContent div -->

	</div> <!-- End mBody div -->

	<div class="visualClear"></div>

	<!-- FOOTER -->
    <div id="footer">
        <?php if($this->data['poweredbyico']) { ?>
            <div id="f-poweredbyico"><?php $this->html('poweredbyico') ?></div>

        <?php 	}
	    if($this->data['copyrightico']) { ?>
	        <div id="f-copyrightico"><?php $this->html('copyrightico') ?></div>

        <?php	} ?>

        <?php
	    // Generate additional footer links
	    $footerlinks = array(
		    //'lastmod', 'viewcount',
		    'numberofwatchingusers', 'credits', 'copyright',
		    'privacy', 'about', 'disclaimer', 'tagline',
	    );
	    ?>

	    <ul>
	    <?php
	    foreach( array('lastmod', 'viewcount') as $aLink ) {
		    if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) { ?>
		        <li id="<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>
        <?php } } ?>
        </ul>

        <?php
	    $validFooterLinks = array();
	    foreach( $footerlinks as $aLink ) {
		    if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
			    $validFooterLinks[] = $aLink;
		    }
	    }

	    if ( count( $validFooterLinks ) > 0 ) { ?>
		    <ul id="f-list">
            <?php foreach( $validFooterLinks as $aLink ) {
			    if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) { ?>
			    <li id="<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>
            <?php } } ?>
            
		    </ul>
        <?php	} ?>
    </div> <!-- End of footer div -->

</div> <!-- End of container div -->
<!--<script src="//www.mozilla.org/tabzilla/media/js/tabzilla.js" async></script>-->
<?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>
<?php $this->html('reporttime') ?>
<?php if ( $this->data['debug'] ): ?>
<!-- Debug output:
<?php $this->text( 'debug' ); ?>

-->
<?php endif; ?>
</body></html>
<?php
wfRestoreWarnings();
} // end of execute() method

/*************************************************************************************************/
function personalTools() {
?>
				<li><span><?php $this->msg('personaltools') ?></span>
				    <ul>
			            <?php foreach($this->data['personal_urls'] as $key => $item) { ?>
                        <li id="pt-<?php echo htmlspecialchars($key) ?>"><a href="<?php echo htmlspecialchars($item['href']) ?>" <?php if(!empty($item['class'])) { ?>
                            class="<?php echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
	                    <?php } ?>
				    </ul>
				</li>
<?php
}
function searchBox() {
	global $wgUseTwoButtonsSearchForm;
?>
<li><span><label for="searchInput"><?php $this->msg('search') ?></label></span>
    <ul>
        <form action="<?php $this->text('wgScript') ?>" id="searchform">
        <input type='hidden' name="title" value="<?php $this->text('searchtitle') ?>"/>
        <input id="searchInput" name="search" type="text"<?php echo $this->skin->tooltipAndAccesskey('search');
        if( isset( $this->data['search'] ) ) { ?> value="<?php $this->text('search') ?>"<?php } ?> />
        <div>
            <input type='submit' name="go" class="searchButton" id="searchGoButton"	value="<?php $this->msg('searcharticle') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-go' ); ?> />
            <?php if ($wgUseTwoButtonsSearchForm) { ?>
                <input type='submit' name="fulltext" class="searchButton" id="mw-searchButton" value="<?php $this->msg('searchbutton') ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?> />
            <?php } else { ?>
                <a href="<?php $this->text('searchaction') ?>" rel="search"><?php $this->msg('powersearch-legend') ?></a>
            <?php } ?>
        </div>
        </form>
    </ul>
</li>
<?php
	}

	/*************************************************************************************************/
	function toolbox() {
?>
<li><span><?php $this->msg('toolbox') ?></span>
    <ul>
        <?php if($this->data['notspecialpage']) { ?>
            <li id="t-whatlinkshere"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey('t-whatlinkshere') ?>><?php $this->msg('whatlinkshere') ?></a></li>

        <?php if( $this->data['nav_urls']['recentchangeslinked'] ) { ?>
		    <li id="t-recentchangeslinked"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey('t-recentchangeslinked') ?>><?php $this->msg('recentchangeslinked') ?></a></li>
        <?php
        } }

		if(isset($this->data['nav_urls']['trackbacklink'])) { ?>
		    <li id="t-trackbacklink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey('t-trackbacklink') ?>><?php $this->msg('trackbacklink') ?></a></li>
        <?php
        }

		if($this->data['feeds']) { ?>
		    <li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) { ?><a id="<?php echo Sanitizer::escapeId( "feed-$key" ) ?>" href="<?php echo htmlspecialchars($feed['href']) ?>" rel="alternate" type="application/<?php echo $key ?>+xml" class="feedlink"<?php echo $this->skin->tooltipAndAccesskey('feed-'.$key) ?>><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;<?php } ?></li>
		<?php
		}

		foreach( array('contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) {
			if($this->data['nav_urls'][$special]) { ?>
				<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey('t-'.$special) ?>><?php $this->msg($special) ?></a></li>
        <?php
        } }

		if(!empty($this->data['nav_urls']['print']['href'])) { ?>
            <li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href']) ?>" rel="alternate"<?php echo $this->skin->tooltipAndAccesskey('t-print') ?>><?php $this->msg('printableversion') ?></a></li><?php
		}

		if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
            <li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])?>"<?php echo $this->skin->tooltipAndAccesskey('t-permalink') ?>><?php $this->msg('permalink') ?></a></li>
        <?php } elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
			<li id="t-ispermalink"<?php echo $this->skin->tooltip('t-ispermalink') ?>><?php $this->msg('permalink') ?></li>
	    <?php
	    }

	    /* This line seems unimportant for this skin
	    wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
	    */

	    // This line triggers that certain extensions can add extra links to the toolbox.
	    wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
        ?>
	</ul>
</li>
<?php
	}

	/*************************************************************************************************/
	function languageBox() {
		if( $this->data['language_urls'] ) {
?>
<li><span><?php $this->msg('otherlanguages') ?></span>
    <ul>
        <?php foreach($this->data['language_urls'] as $langlink) { ?>
		<li class="<?php echo htmlspecialchars($langlink['class'])?>"><a href="<?php echo htmlspecialchars($langlink['href']) ?>"><?php echo $langlink['text'] ?></a></li>
        <?php } ?>
	</ul>
</li>
<?php
		}
	}

	/*************************************************************************************************/
	function customBox( $bar, $cont ) {
?>
<li><span><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></span>
    <?php if ( is_array( $cont ) ) { ?>
    <ul>
        <?php foreach($cont as $key => $val) { ?>
	    <li id="<?php echo Sanitizer::escapeId($val['id']) ?>"<?php if ( $val['active'] ) { ?> class="active" <?php } ?>><a href="<?php echo htmlspecialchars($val['href']) ?>"<?php echo $this->skin->tooltipAndAccesskey($val['id']) ?>><?php echo htmlspecialchars($val['text']) ?></a></li>
        <?php } ?>
    </ul>
    <?php } else {
		# allow raw HTML block to be defined by extensions
		print $cont;
	}
?>
</li>
<?php
}

} // end of class


