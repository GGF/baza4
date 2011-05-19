<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : calend.xslt.xsl
    Created on : 19 Май 2011 г., 15:00
    Author     : igor
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <xsl:template match="/">
        <xsl:apply-templates select="/rss/channel/item"/>
    </xsl:template>   
    
    <xsl:template match="item">

        <div>
            <a target="_blank">
                <xsl:attribute name="href"><xsl:value-of select="./link"/></xsl:attribute>
                <xsl:value-of select="./description"/>
            </a>
        </div>
    </xsl:template>

</xsl:stylesheet>
