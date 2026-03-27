# Paperless Integration

Integration with the [Paperless](https://docs.paperless-ngx.com) Document Management System.  
It adds a file action menu item that can be used to upload a file from your Nextcloud Files to Paperless.

## **🛠️ State of maintenance**

While there are many things that could be done to further improve this app, the app is currently maintained with **limited effort**. This means:

- The main functionality works for the majority of the use cases
- We will ensure that the app will continue to work like this for future releases and we will fix bugs that we classify as 'critical'
- We will not invest further development resources ourselves in advancing the app with new features
- We do review and enthusiastically welcome community PR's

We would be more than excited if you would like to collaborate with us. We will merge pull requests for new features and fixes. We also would love to welcome co-maintainers.

If there is a strong business case for any development of this app, we will consider your wishes for our roadmap. Please [contact your account manager](https://nextcloud.com/enterprise/) to talk about the possibilities.

## Development

The Paperless server will run on `http://localhost:8000` with username `admin` and password `admin`:
```bash
docker compose up
```
