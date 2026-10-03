# Changelog

## Unreleased

## v0.2.0 - 2026-10-03

- Retry transient connection and HTTP 5xx failures only for GET requests. VIN decode POST requests are sent once to avoid a second quota-counted decode when a response is lost.
- Cover every public resource method against the documented API v1 route and HTTP method.
- Verify VIN decode is not retried after connection failures or HTTP 5xx responses, while GET retries remain available.

## v0.1.1 - 2026-09-25

- Retry only connection failures and HTTP 5xx responses, with retry counts
  representing additional attempts.
- Preserve Laravel validation error messages in typed exceptions.

## v0.1.0

- Initial release
- Automotive API
- Powersports API
- VIN Decoder
- Laravel 11, 12, and 13 support
- Configurable API version
- Retry support
- Rate limit information
