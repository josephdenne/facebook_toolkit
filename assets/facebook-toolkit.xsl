<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="debug-facebook-toolkit">
		<xsl:if test="/data/events/facebook/@is-admin or /data/params/cookie-username">
			<div id="facebook-toolkit-debug-container">
				<style type="text/css">
					#facebook-toolkit-debug-container {
						position: fixed;
						width: 200px;
						top: 0;
						left: 0;
						height: 200px;
						opacity: 0.8;
						border: 1px solid red;
						background: #ccc;
						color: white;
					}
				</style>
				<div>
					Liked: <xsl:value-of select="/data/events/facebook/@page-liked" />
				</div>
				<div>
					Logged in: <xsl:value-of select="/data/events/facebook/@logged-in" />
				</div>
				<div>
					<a href="https://www.facebook.com/dialog/pagetab?app_id={$facebook-application-id}
			&amp;display=popup&amp;next={$root}">
						Add this App as a page tab to an existing page.
					</a>
				</div>
			</div>
		</xsl:if>
	</xsl:template>

</xsl:stylesheet>