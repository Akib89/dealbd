---
name: DealBD Bazaar Bulletin
description: A contemporary community-edited deal circular for Bangladesh.
seed: seed:0aa623d3c48423ba
colors:
  paper: "oklch(96.5% 0.022 82)"
  ink: "oklch(21% 0.028 45)"
  vermilion: "oklch(51% 0.2 29)"
  mustard: "oklch(79% 0.14 83)"
  success: "oklch(44% 0.12 150)"
typography:
  family: "Instrument Sans"
  display: "600 / 0.92 / -0.04em"
  body: "400 / 1.55"
rounded:
  control: "3px"
  surface: "6px"
---

# Design System: DealBD Bazaar Bulletin

## Creative direction

DealBD behaves like a community-edited weekly deal circular rather than a generic marketplace. Price, conditions, store, score, and discussion are the editorial material. The interface is warm and public-facing, but never casual about trust.

The signature is the deal bulletin: community confidence occupies a fixed lane, the offer evidence stays central, and the price receives its own column. On the home page, the working filter desk is part of the opening composition rather than a utility added below a marketing hero.

## Product truth

- Bangladesh focus and BDT pricing remain explicit.
- Community votes and discussion are evidence, not guarantees.
- Merchant involvement, affiliate links, coupons, payment conditions, and expiry remain visible.
- Price history is manually submitted and must never imply automated market monitoring.
- No testimonials, partner logos, or adoption metrics appear without verified material.

## Color

Warm paper is the default ground. Charcoal ink carries information. Vermilion identifies DealBD and the primary action. Mustard marks the active search desk and genuine editorial emphasis. Green, amber, and red remain semantic only.

All color values live in `public/css/tokens.css`. Components consume role tokens; they do not introduce color literals.

## Typography

Instrument Sans is self-hosted and serves both display and interface roles. Display hierarchy comes from scale, compression, and weight rather than a second decorative family. Headings use one color and no inline novelty styling. Labels remain sentence case and untracked.

Prices, vote scores, counts, and table figures use tabular numerals.

## Layout

The maximum content width is 1320px. The public feed uses a fluid bulletin lane and a 260px browse rail. Deal rows preserve fixed score and price lanes until the mobile breakpoint, where price moves below the evidence without losing reading order.

Operational screens keep familiar forms and tables. Their brand character comes from rules, paper surfaces, compact type, and precise state color rather than ornamental layouts.

## Components

### Masthead

A compact sticky masthead holds the wordmark, feed views, account actions, and one primary contribution action. On smaller screens the feed navigation becomes a horizontal second row.

### Deal bulletin

Each deal row contains:

- community score seal;
- category, store, title, description, author, age, and discussion;
- price, original price, and discount when supplied;
- one details action on wider screens.

### Controls

Controls are rectangular, compact, and at least 44px high. Focus appears instantly with a high-contrast outline. Hover, active, disabled, loading, error, and success states use color, copy, and native attributes without layout shifts.

### Surfaces

Working surfaces use thin structural rules and small radii. Shadows are reserved for genuinely raised layers. Nested cards, decorative gradients, glass effects, and routine icon tiles are not part of the system.

## Responsive behavior

- At 60rem, major columns stack and the masthead navigation moves to its own row.
- At 40rem, deal rows become two-column score-and-evidence entries with price beneath the evidence.
- Tables remain horizontally scrollable when honest column reduction is not possible.
- Buttons and navigation labels remain single-line, and coarse pointers receive 48px targets.
- Reduced-motion preferences remove all nonessential movement.

## Accessibility

Every interactive element must expose `:focus-visible`. Labels remain visible above fields. Error states use text as well as color. The page works without JavaScript, reading order matches visual order, and horizontal page overflow is clipped at both root elements.
