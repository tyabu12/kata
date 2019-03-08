# Gitlet

[![CircleCI](https://circleci.com/gh/tyabu12/kata/tree/gitlet.svg?style=svg)](https://circleci.com/gh/tyabu12/kata/tree/gitlet)

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
$ yarn build
$ node
> const gitlet = require("./build");
> gitlet.init();
{ '.gitlet':
   { HEAD: 'ref: refs/heads/master\n',
     config: '[core]\n  bare = false\n',
     objects: {},
     refs: { heads: {} } } }
```
