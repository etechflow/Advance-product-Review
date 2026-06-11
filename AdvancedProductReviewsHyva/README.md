# ETechFlow Advanced Product Reviews — Hyvä Compatibility

Renders the Advanced Product Reviews UI inside the **Hyvä** theme using Tailwind
CSS + Alpine.js. There is no jQuery/RequireJS dependency: the whole storefront
component is driven by the base module's **GraphQL API** (added in Phase 7), so
it doubles as a reference implementation of the headless layer.

## Requirements
- `ETechFlow_AdvancedProductReviews` (base module)
- A Hyvä theme (`hyva-themes/magento2-theme-module`) — **enable this module only
  on stores running the Hyvä theme.** It targets the Hyvä `product.info.details`
  container and is not intended for the Luma theme.

## What it does
- Product review **summary** (average, star distribution, recommend %, verified
  count) via `etfReviewSummary`.
- **Enhanced review list** with pros/cons, verified/recommends badges, photo &
  video thumbnails, comments, helpful voting and per-review translation — via
  `etfProductReviews`, `etfVoteReviewHelpful`, `etfTranslateReview`.
- **Q&A** list and "ask a question" form via `etfProductQuestions` and
  `etfAskProductQuestion`.
- Client-side filters (rating / verified / with-media), sorting and pagination,
  all re-querying GraphQL.

## Install
```
bin/magento module:enable ETechFlow_AdvancedProductReviewsHyva
bin/magento setup:upgrade
```

## Headless API surface (base module)
- GraphQL: `etfReviewSummary`, `etfProductReviews`, `etfProductQuestions`,
  `etfVoteReviewHelpful`, `etfPostReviewComment`, `etfAskProductQuestion`,
  `etfTranslateReview`; plus `ProductInterface.etf_review_summary`.
- REST: `GET /V1/etechflow-reviews/summary/product/:productId`,
  `GET /V1/etechflow-reviews/summary/sku/:sku`,
  `GET /V1/etechflow-reviews/extra/review/:reviewId`, and admin-protected
  extra-data management endpoints.
