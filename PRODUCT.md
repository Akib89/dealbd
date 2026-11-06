# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

The primary users are people in Bangladesh who want to discover, assess, share, and track genuine local deals. Registered community members post deals, vote, comment, save offers, report problems, and create alerts. Merchants submit disclosed offers and review their deal status and outbound clicks. Moderators and administrators review submissions, reports, comments, and user roles.

## Product Purpose

DealBD is a Bangladesh-focused, community-driven deals platform. It helps people find worthwhile offers, judge them with community input, and act before they expire. Success means useful deals are easy to discover, questionable information is surfaced and moderated, and members can contribute without losing trust in the feed.

## Positioning

DealBD relies on community verification through voting, comments, reporting, staff moderation, and matching alerts. It does not claim automatic merchant price monitoring or comprehensive market-price coverage.

## Operating Context

Visitors browse hot, new, and top deal feeds; search and filter by category, store, and payment method; and inspect deal details before following an outbound merchant link. Members submit and edit deals, disclose merchant involvement and conditions, discuss offers, save deals, and receive in-app alerts. Staff approve, reject, feature, expire, and investigate content. Prices are presented in Bangladeshi taka and times use the Bangladesh context.

## Capabilities and Constraints

- Laravel web application with account, deal, voting, commenting, bookmarking, reporting, alert, notification, moderation, and merchant-dashboard workflows.
- Deal submissions and significant edits require moderation before publication.
- Price history consists of manually submitted observations, not automated scraping.
- Alerts are in-app database notifications for newly approved matching deals.
- Merchant involvement, affiliate links, coupon requirements, payment conditions, and offer limitations must be disclosed accurately.
- This is currently a substantial starter, not a production-complete marketplace. The repository documents security, compliance, moderation, scale, and operational work still required before public production use.

## Brand Commitments

- Product name: DealBD.
- Bangladesh focus and BDT pricing.
- Independent, community-led savings positioning.
- Clear merchant and affiliate disclosure.
- Claims must not imply automatic price monitoring, complete market coverage, or capabilities not implemented in the product.

## Evidence on Hand

- Implemented Laravel routes, controllers, models, Blade views, migrations, seed data, and feature tests in this repository.
- Product scope and limitations are documented in `README.md` and `DOCKER_SETUP.md`.
- Seeded categories and store examples support local development demonstrations.
- No confirmed testimonials, customer logos, usage benchmarks, press coverage, production traffic data, or merchant partnerships are present; future work must not fabricate them.

## Product Principles

1. Earn trust through transparent community signals and accountable moderation.
2. Make local deal discovery fast and relevant to shoppers in Bangladesh.
3. Keep commercial involvement and offer conditions explicit.
4. Distinguish observed facts from estimates, examples, and unimplemented capabilities.
5. Support contribution while protecting the quality of the public deal feed.

## Accessibility & Inclusion

The responsive web interface should remain usable across common desktop and mobile browsers. No additional product-specific accessibility standard has been confirmed.
