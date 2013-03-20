<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="debug-facebook-toolkit">
		<xsl:if test="/data/events/facebook/@is-admin or /data/params/cookie-username">
			<div id="facebook-toolkit-debug-container">
				<style type="text/css">
					#facebook-toolkit-debug-container {
						position: fixed;
						width: 100px;
						top: 5px;
						left: 5px;
						opacity: 0.9;
						border: 3px solid #111;
						background: #444;
						color: white;
						z-index: 1000;
						font-size: 10px;
						line-height: 1.5;
						padding: 4px;
						border-radius: 4px;
					}

					#facebook-toolkit-debug-container a {
						display: block;
						color: #eee;
						text-decoration: underline;
					}
				</style>
				<div>
					Liked: <xsl:value-of select="/data/events/facebook/@page-liked" />
				</div>
				<div>
					Logged in: <xsl:value-of select="/data/events/facebook/@logged-in" />
				</div>
				<div>
					<a href="https://www.facebook.com/dialog/pagetab?app_id={$facebook-application-id}&amp;display=popup&amp;next=http://facebook.com/">
						Add this App as a page tab to an existing page.
					</a>
				</div>
			</div>
		</xsl:if>
	</xsl:template>

</xsl:stylesheet>