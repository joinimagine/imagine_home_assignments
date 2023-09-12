**Project:** DoT Proxy Server

**Objective:**

The following challenge is to write a DNS proxy that listens to conventional DNS and sends it over
TLS (detailed requirements below).

Nowadays, some DNS providers (such as Cloudflare) offer a DNS-over-TLS feature that could let
us enhance privacy by encrypting our DNS queries.
Our applications don't handle DNS-over-TLS by default. Your task is to design and create a
simple DNS to DNS-over-TLS proxy that we could use to enable our application to query a
DNS-over-TLS server.

**Requirements:**

- From your understanding of the topic, we would expect a working example of a DNS to
  DNS-over-TLS proxy that can:

  - Handle at least one DNS query, and give a result to the client.
  - Work over TCP and talk to a DNS-over-TLS server that works over TCP (e.g: Cloudflare).

- **Bonus:**

If you still want to have fun, we’d love to see you give these features a try:

- Allow multiple incoming requests at the same time
- Also handle UDP requests, while still querying tcp on the other side
- Any other improvements you can think of!

**Tools:**

Here is some helpful documentation:

- [Cloudflare's explanation of DNS-over-TLS;](https://developers.cloudflare.com/1.1.1.1/encryption/dns-over-tls/) you can use Cloudflare’s DNS-over-TLS
  feature to test your queries.
- Feel free to look into these RFCs:
  - [DNS](https://datatracker.ietf.org/doc/html/rfc1035) (especially [UDP vs. TCP implementations](https://datatracker.ietf.org/doc/html/rfc1035#section-4.2.2))
  - [DNS-over-tls](https://datatracker.ietf.org/doc/html/rfc7858)
- Use any resource available to you online except third party applications or libraries that do
  everything for you, at the end we are evaluating your code.
- Use any language you see fit, and any libraries to leverage code already written by
  modules and packages.
- If needed, here are some links to libraries regarding implementations
  - Golang: [tls](https://golang.org/pkg/crypto/tls/), [net (udp/tcp lib)](https://golang.org/pkg/net/)
  - Python: [ssl](https://docs.python.org/3/library/ssl.html), [socket (udp/tcp)](https://docs.python.org/3/library/socket.html)
