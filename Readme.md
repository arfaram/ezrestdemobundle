#EzRestBundle

Extending the Ez Rest API - demo 

## Requirement

eZPlatform 1.7 + , symfony 2.8 +

## Use Case -Demo

See presentation to understand better how to extend the ezplatform REST API:https://github.com/arfaram/ezrestdemobundle

- Export all content using specific contentType ID from specific path. content fieldTypes should be specified.
- How to use GET or POST verbs using `ValueObjectVisitor` and `InputParser`

 See List of required and optional parameters and examples below
 
##Installation
- create vendor and bundle folder inside your src folder

```
mkdir -p src/Ez/RestBundle
```

- Clone the Repo in the RestBundle folder

```
git clone https://github.com/arfaram/ezrestdemobundle.git
```
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


### Mandatory
- `subtree`:  Specify the location from where content will be fetched.(default:2)
- `fields`: ***Note: only ezstring, ezrichtext and ezimage fields are supported in this demo ***. 

### Optional
- `limit`: Number of content items to return
- `sort`: ContentName sorting. Possible values: `ASC` or `DESC`. (default:*ASC*) 
- `lang`: Content Language (e.g. `ger-DE`)
- `hidden`: `true` or `false` fetch visible or hidden content. (default:*true* )
- `image_variation`: (default:*original*)

## GET Example

```
Method: GET
Host:http://wwww.domain.com/api/ezp/v2/ez_rest/contentTypeContent/GET/48?&limit=2&subtree=99&fields=title,summary,description,main_image&sort=DESC&lang=ger-DE&image_variation=small&hidden=true

Accept:application/xml
```
-> Returns content with contenttype id:48 and parent locationId:99. 

See XML and JSON examples below

## POST Example
```
Method: POST
Host:http://wwww.domain.com/api/ezp/v2/ez_rest/contentTypeContent/POST

X-CSRF-Token:xxxxxxxxxxxxxxxxxxxxxxxxxx
Content-Type:application/vnd.custom.ContentTypeContentList
Accept:application/xml
```
XML payload
```
<?xml version="1.0" encoding="utf-8"?>
<ContentTypeContentList>
    <contentTypeId>50</contentTypeId>
    <limit>2</limit>
    <subtree>109</subtree>
    <sort>DESC</sort>
    <hidden>true</hidden>
    <lang>ger-DE</lang>
    <image_variation>small</image_variation>
    <fields>
    	<field>
    		<fieldDefinitionIdentifier>title</fieldDefinitionIdentifier>
    	</field>
     	<field>
    		<fieldDefinitionIdentifier>summary</fieldDefinitionIdentifier>
    	</field>
     	<field>
    		<fieldDefinitionIdentifier>description</fieldDefinitionIdentifier>
    	</field>
    	<field>
    		<fieldDefinitionIdentifier>main_image</fieldDefinitionIdentifier>
    	</field>
    </fields>
</ContentTypeContentList>
```

#### XML output example
```
<?xml version="1.0" encoding="UTF-8"?>
<contentList media-type="application/vnd.ez.api.contentList+xml">
    <content media-type="application/vnd.ez.api.content+xml">
        <contentId>101</contentId>
        <contentTypeId>48</contentTypeId>
        <identifier>blog_post</identifier>
        <language>ger-DE</language>
        <publishedDate>2017-07-09T22:15:13+02:00</publishedDate>
        <uri>/Trips/Article</uri>
        <categoryPath>/1/2/94/95/99/101/</categoryPath>
        <mainLocation media-type="application/vnd.ez.api.mainLocation+xml" href="/api/ezp/v2/content/locations/1/2/94/95/99/101/"/>
        <locations media-type="application/vnd.ez.api.locations+xml" href="/api/ezp/v2/content/objects/101/locations"/>
        <title>Article</title>
        <summary>&lt;![CDATA[&lt;section xmlns=&quot;http://ez.no/namespaces/ezpublish5/xhtml5&quot;&gt;&lt;p&gt;THE SUMMARY.&lt;/p&gt;&lt;/section&gt;
]]&gt;</summary>
        <description>&lt;![CDATA[&lt;section xmlns=&quot;http://ez.no/namespaces/ezpublish5/xhtml5&quot;&gt;&lt;p&gt;THE DESCRIPTION&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;/section&gt;
]]&gt;</description>
        <main_image>http://www.domain.com/var/site/storage/images/_aliases/small/7/7/4/0/477-1-ger-DE/article.jpg</main_image>
    </content>
</contentList>
```
#### Json output example
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
                "uri": "/Trips/Article",
                "categoryPath": "/1/2/94/95/99/101/",
                "mainLocation": {
                    "_media-type": "application/vnd.ez.api.mainLocation+json",
                    "_href": "/api/ezp/v2/content/locations/1/2/94/95/99/101/"
                },
                "locations": {
                    "_media-type": "application/vnd.ez.api.locations+json",
                    "_href": "/api/ezp/v2/content/objects/101/locations"
                },
                "title": "Article",
                "summary": "<![CDATA[<section xmlns=\"http://ez.no/namespaces/ezpublish5/xhtml5\"><p>THE SUMMARY.</p></section>\n]]>",
                "description": "<![CDATA[<section xmlns=\"http://ez.no/namespaces/ezpublish5/xhtml5\"><p>THE DESCRIPTION</p></section>\n]]>",
                "main_image": "http://www.domain.com/var/site/storage/images/_aliases/small/7/7/4/0/477-1-ger-DE/article.jpg"
            }
        ]
    }
}
```



