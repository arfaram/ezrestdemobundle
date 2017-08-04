#EzRestBundle
Extending the Ez Rest API - demo 

eZplatform 1.7 + , symfony 2.8 +

##Usage

- Return all Contenttype content from specific path using different parameters. See *Request Parameters* and *Example* below

##Installation
- create vendor folder inside your src folder

mkdir src/Ez

- Clone the Repo in the Ez folder

git clone git@bitbucket.org:Ramzi-Arfaoui/ezrestbundle.git

- Activate the Bundle in AppKernel.php

```
    public function registerBundles()
    {
        $bundles = array(
            //...
            new Ez\RestBundle\EzRestBundle(),

```

- Add the bundle routing in routing.yml
```
ez_rest:
    resource: "@EzRestBundle/Resources/config/routing.yml"
    prefix:   /
```

- Clear the cache
```
php app/console cache:clear
```

## Request Parameters

- limit(optional): Number of content items to return
- subtree(optioanl): depend on your site setting (e.g Multisite configuration).Per default from the siteaccess root location. You can specifiy your parent location by setting this parameter to your subtree parent LocationId.
- Fields(mandatory): ***Only ezstring, ezrichtext and ezimage will be served form this demo implementation***. More infos in: Rest/Content/Content.php . You can add other fields using the same logic. ezimage returns the "original" image variation. 
- sort(optional): ContentName Sorting. Possible values: *ASC* or *DESC*. (default:*ASC*) 
- lang(optional): Content Language
- hidden(optional): Setting this value to 0 or 1 will fetch visible and hidden content. Default: not set (only visible)

##Example

```
Method: GET
Host:http://wwww.domain.com/api/ezp/v2/ez_rest/contentTypeContent/48?&limit=2&subtree=99&fields=title,summary,description,main_image&sort=DESC

Accept:application/xml
```
-> Return content of contenttype id:48 from the parent locationId:99. 

###XML output example
```
<?xml version="1.0" encoding="UTF-8"?>
<contentList media-type="application/vnd.ez.api.contentList+xml">
    <content media-type="application/vnd.ez.api.content+xml">
        <contentId>101</contentId>
        <contentTypeId>48</contentTypeId>
        <identifier>blog_post</identifier>
        <language>ger-DE</language>
        <publishedDate>2017-07-09T22:15:13+02:00</publishedDate>
        <uri>/Trips/Koelner-Weihnachtsmarkt</uri>
        <categoryPath>/1/2/94/95/99/101/</categoryPath>
        <mainLocation media-type="application/vnd.ez.api.mainLocation+xml" href="/api/ezp/v2/content/locations/1/2/94/95/99/101/"/>
        <locations media-type="application/vnd.ez.api.locations+xml" href="/api/ezp/v2/content/objects/101/locations"/>
        <title>Kölner Weihnachtsmarkt</title>
        <summary>&lt;![CDATA[&lt;section xmlns=&quot;http://ez.no/namespaces/ezpublish5/xhtml5&quot;&gt;&lt;p&gt;Der Weihnachtsmarkt am Dom steht unangefochten ganz oben auf der Besucherliste f&amp;uuml;rs Weihnachtsshopping: 150 festlich geschm&amp;uuml;ckte St&amp;auml;nde f&amp;uuml;r Kunsthandwerk und Gastronomie sowie &amp;uuml;ber hundert kostenfreie, weihnachtliche B&amp;uuml;hnenveranstaltungen locken j&amp;auml;hrlich an die vier Millionen Besucher aus dem In- und Ausland an den Dom.&lt;/p&gt;&lt;/section&gt;
]]&gt;</summary>
        <description>&lt;![CDATA[&lt;section xmlns=&quot;http://ez.no/namespaces/ezpublish5/xhtml5&quot;&gt;&lt;p&gt;Die Heimat der Heinzel ist die Altstadt und der&amp;nbsp; Legende nach verrichteten diese Heinzelm&amp;auml;nnchen ganz unterschiedliche Arbeiten f&amp;uuml;r die K&amp;ouml;lner: Dem Metzger machten sie W&amp;uuml;rste, dem Schneider n&amp;auml;hten sie Kleider und dem B&amp;auml;cker backten sie Brot. Entsprechend sind auch die verwinkelten Gassen des Weihnachtsmarktes, wie einst die Z&amp;uuml;nfte, in unterschiedliche Themen aufgeteilt.&lt;/p&gt;&lt;p&gt;In die m&amp;auml;rchenhafte Atmosph&amp;auml;re f&amp;uuml;gt sich eine spektakul&amp;auml;re Eislauf-Erlebniswelt ein.&amp;nbsp;Mit gro&amp;szlig;er Eisfl&amp;auml;che, langen Laufwegen &amp;uuml;ber den gesamten Platz und um das imposante Reiterdenkmal, Bahnen zum Eisstockschie&amp;szlig;en und einer Br&amp;uuml;cke &amp;uuml;bers Eis.&lt;/p&gt;&lt;p&gt;F&amp;uuml;r die musikalischen Acts sorgen Bands wie Cat Ballou und La M&amp;auml;ng, Stefan Knittler mit &amp;bdquo;Loss mer singe&amp;ldquo;, die Domst&amp;auml;dter Big Band mit ihrer &amp;bdquo;Verrockten Weihnacht&amp;ldquo; und der K&amp;ouml;lner Jugendchor St. Stephan&lt;/p&gt;&lt;p&gt;&lt;strong&gt;&amp;Ouml;ffnunsgzeiten:&lt;/strong&gt; Bis 23. Dezember. So-Mi 11-21 Uhr, Do-Fr bis 22 Uhr, Sa 10-22 Uhr&lt;/p&gt;&lt;p&gt;&lt;strong&gt;Adresse:&lt;/strong&gt; Roncalliplatz 1, 50667 K&amp;ouml;ln&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/section&gt;
]]&gt;</description>
        <main_image>http://www.domain.com/var/site/storage/images/7/7/4/0/477-1-ger-DE/wmarkt_koeln.jpg</main_image>
    </content>
</contentList>
```
###Json output example
```
Accept:application/json
```

```
{
    "contentList": {
        "_media-type": "application/vnd.ez.api.contentList+json",
        "content": [
            {
                "_media-type": "application/vnd.ez.api.content+json",
                "contentId": 101,
                "contentTypeId": 48,
                "identifier": "blog_post",
                "language": "ger-DE",
                "publishedDate": "2017-07-09T22:15:13+02:00",
                "uri": "/Trips/Koelner-Weihnachtsmarkt",
                "categoryPath": "/1/2/94/95/99/101/",
                "mainLocation": {
                    "_media-type": "application/vnd.ez.api.mainLocation+json",
                    "_href": "/api/ezp/v2/content/locations/1/2/94/95/99/101/"
                },
                "locations": {
                    "_media-type": "application/vnd.ez.api.locations+json",
                    "_href": "/api/ezp/v2/content/objects/101/locations"
                },
                "title": "Kölner Weihnachtsmarkt",
                "summary": "<![CDATA[<section xmlns=\"http://ez.no/namespaces/ezpublish5/xhtml5\"><p>Der Weihnachtsmarkt am Dom steht unangefochten ganz oben auf der Besucherliste f&uuml;rs Weihnachtsshopping: 150 festlich geschm&uuml;ckte St&auml;nde f&uuml;r Kunsthandwerk und Gastronomie sowie &uuml;ber hundert kostenfreie, weihnachtliche B&uuml;hnenveranstaltungen locken j&auml;hrlich an die vier Millionen Besucher aus dem In- und Ausland an den Dom.</p></section>\n]]>",
                "description": "<![CDATA[<section xmlns=\"http://ez.no/namespaces/ezpublish5/xhtml5\"><p>Die Heimat der Heinzel ist die Altstadt und der&nbsp; Legende nach verrichteten diese Heinzelm&auml;nnchen ganz unterschiedliche Arbeiten f&uuml;r die K&ouml;lner: Dem Metzger machten sie W&uuml;rste, dem Schneider n&auml;hten sie Kleider und dem B&auml;cker backten sie Brot. Entsprechend sind auch die verwinkelten Gassen des Weihnachtsmarktes, wie einst die Z&uuml;nfte, in unterschiedliche Themen aufgeteilt.</p><p>In die m&auml;rchenhafte Atmosph&auml;re f&uuml;gt sich eine spektakul&auml;re Eislauf-Erlebniswelt ein.&nbsp;Mit gro&szlig;er Eisfl&auml;che, langen Laufwegen &uuml;ber den gesamten Platz und um das imposante Reiterdenkmal, Bahnen zum Eisstockschie&szlig;en und einer Br&uuml;cke &uuml;bers Eis.</p><p>F&uuml;r die musikalischen Acts sorgen Bands wie Cat Ballou und La M&auml;ng, Stefan Knittler mit &bdquo;Loss mer singe&ldquo;, die Domst&auml;dter Big Band mit ihrer &bdquo;Verrockten Weihnacht&ldquo; und der K&ouml;lner Jugendchor St. Stephan</p><p><strong>&Ouml;ffnunsgzeiten:</strong> Bis 23. Dezember. So-Mi 11-21 Uhr, Do-Fr bis 22 Uhr, Sa 10-22 Uhr</p><p><strong>Adresse:</strong> Roncalliplatz 1, 50667 K&ouml;ln</p><p>&nbsp;</p></section>\n]]>",
                "main_image": "http://www.domain.com/var/site/storage/images/7/7/4/0/477-1-ger-DE/wmarkt_koeln.jpg"
            }
        ]
    }
}
```
##TODO
- Separate the Content and the Field Values implementation in Content.php
- Refactoring the ValueObjectVisitor/ContentData.php
