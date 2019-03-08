# Gitlet

To understand Git mechanism.

I'm NOT author of Gitlet (maryrosecook does) .
This is a memo for my study.

## Document

<http://gitlet.maryrosecook.com/docs/gitlet.html>

## Original Source code

<https://github.com/maryrosecook/gitlet>

## Usage

```bash
$ yarn install
$ node
> const gitlet = require("./src");
> gitlet.init();
{ '.gitlet':
   { HEAD: 'ref: refs/heads/master\n',
     config: '[core]\n  bare = false\n',
     objects: {},
     refs: { heads: {} } } }
```
