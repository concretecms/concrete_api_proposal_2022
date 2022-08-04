# Concrete CMS API Proposal 2022

Hello there! This is a package for Concrete CMS (9.1.1+) that adds a proposed REST API. This API is reasonably comprehensive: there are certainly many things you won't be able to do with this API that you can do with the full CMS application, but this API should give you significant access to the most important aspects of a Concrete CMS application.

## The Purpose of this Package

This package demonstrates a comprehensive REST API. It demonstrates a proposed format for REST calls, including API scopes, endpoints, schemas, formats and operations. This proposal package has been built to be fully functional – meaning the documented operations in this package should actually work with a real site – but that doesn't mean it's fully tested and ready for production. Instead, this package is meant to demonstrate a proposed API, spark discussion about the API, and work as a spec.

Now that this proposed API is available, I hope that developers will install it somewhere, take a look at its proposals, kick the tires, and let me know if they think it's perfect everywhere (:fingerscrossed) or whether it needs some tweaks (more likely!)

Ultimately, this API – including any enhancements or tweaks that come about during this testing phase – is slated for inclusion in Concrete CMS 9.2.0 this fall.

## Installation

Clone this repository into your testing site's `packages/` directory.

Within your testing site, enable the Concrete CMS REST API on Dashboard > System and Settings > API > Settings. Do not create an integration at this time, it will be created for you when the package is installed.

For the redirect URL in this API integration, specify `http://www.yoursiteurl.com/packages/concrete_api_proposal_2022/swagger/oauth2-redirect.html`

Now, install the "Concrete CMS API Proposal" package into your site.

## Try it Out

API proposals can be found in Dashboard > API Proposal. Clicking on this page will redirect you into a REST API page. From this page, click the "View API Documentation Console" button. This will launch standard REST API docs, powered by [Swagger UI](https://swagger.io/tools/swagger-ui/).

<img width="1484" alt="Screen Shot 2022-08-04 at 2 58 58 PM" src="https://user-images.githubusercontent.com/527809/182960023-a31e1fe7-2f0c-4311-a44a-c7bfefb59dde.png">

## Demonstration Video

For a quick walkthrough of installation and a demonstration of a couple REST endpoints in the new proposal, watch this video:

https://www.loom.com/share/d3f22bd837a44105a5c6b5ae982bcfe3

## GraphQL

Yes, we are planning on a GraphQL API in addition to a REST API. No, that work has not yet been started. 

## Feedback

[tbd fill in relevant forum threads and github issue.]

--
@aembler
